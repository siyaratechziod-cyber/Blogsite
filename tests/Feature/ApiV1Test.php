<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiV1Test extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $author;
    protected User $reader;
    protected Category $category;
    protected Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create(['name' => 'Admin User', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role' => 'admin'])->fresh();
        $this->author = User::create(['name' => 'Author User', 'email' => 'author@test.com', 'password' => Hash::make('password'), 'role' => 'author', 'specialty' => 'Backend'])->fresh();
        $this->reader = User::create(['name' => 'Reader User', 'email' => 'reader@test.com', 'password' => Hash::make('password'), 'role' => 'reader'])->fresh();

        $this->category = Category::create(['name' => 'Web Development', 'slug' => 'web-development']);

        $this->post = Post::create([
            'user_id' => $this->author->id,
            'category_id' => $this->category->id,
            'title' => 'Scaling Web Apps',
            'slug' => 'scaling-web-apps',
            'excerpt' => 'An excerpt on scaling.',
            'content' => 'Full technical content on building resilient distributed systems.',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'views' => 100,
        ]);

        $this->post->tags()->attach(Tag::create(['name' => 'Laravel', 'slug' => 'laravel'])->id);
    }

    public function test_register_login_me_logout_flow(): void
    {
        $this->postJson('/api/v1/auth/register', [
            'name' => 'New Person',
            'email' => 'new@test.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'terms' => true,
        ])->assertCreated()->assertJsonStructure(['token', 'user' => ['id', 'email', 'username']]);

        $token = $this->postJson('/api/v1/auth/login', ['email' => 'new@test.com', 'password' => 'secret123'])
            ->assertOk()
            ->json('token');

        $this->withToken($token)->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('user.email', 'new@test.com');

        $this->withToken($token)->postJson('/api/v1/auth/logout')->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 1); // only the register token remains
    }

    public function test_login_rejects_bad_password_and_inactive_accounts(): void
    {
        $this->postJson('/api/v1/auth/login', ['email' => 'reader@test.com', 'password' => 'wrong'])
            ->assertStatus(422)->assertJsonValidationErrors('email');

        $this->reader->update(['is_active' => false]);

        $this->postJson('/api/v1/auth/login', ['email' => 'reader@test.com', 'password' => 'password'])
            ->assertForbidden();
    }

    public function test_deactivated_token_is_rejected(): void
    {
        Sanctum::actingAs($this->reader);
        $this->reader->update(['is_active' => false]);

        $this->getJson('/api/v1/auth/me')->assertForbidden();
    }

    public function test_guest_gets_json_401_without_accept_header(): void
    {
        $this->post('/api/v1/auth/logout')->assertUnauthorized()->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_public_content_endpoints(): void
    {
        $this->getJson('/api/v1/home')->assertOk()
            ->assertJsonStructure(['featured', 'trending', 'latest', 'categories', 'featured_authors', 'testimonials', 'stats']);

        $this->getJson('/api/v1/posts?category=web-development&sort=popular')->assertOk()
            ->assertJsonPath('data.0.slug', 'scaling-web-apps')
            ->assertJsonPath('data.0.tags.0.slug', 'laravel')
            ->assertJsonStructure(['data', 'links', 'meta' => ['current_page', 'last_page', 'total']]);

        $this->getJson('/api/v1/posts/scaling-web-apps')->assertOk()
            ->assertJsonPath('data.content', $this->post->content)
            ->assertJsonStructure(['data' => ['author' => ['avatar_url'], 'comments'], 'related']);
        $this->assertSame(101, $this->post->fresh()->views);

        $this->getJson('/api/v1/posts/does-not-exist')->assertNotFound();

        $this->getJson('/api/v1/categories')->assertOk()->assertJsonPath('data.0.posts_count', 1);
        $this->getJson('/api/v1/categories/web-development')->assertOk()
            ->assertJsonPath('category.slug', 'web-development')
            ->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/tags')->assertOk()->assertJsonPath('data.0.posts_count', 1);

        $this->getJson('/api/v1/authors')->assertOk()->assertJsonStructure(['data', 'meta', 'specialties']);
        $this->getJson('/api/v1/authors/' . $this->author->username)->assertOk()
            ->assertJsonPath('data.posts_count', 1)
            ->assertJsonMissingPath('data.email');
        $this->getJson('/api/v1/authors/' . $this->author->username . '/posts')->assertOk()->assertJsonCount(1, 'data');

        $this->getJson('/api/v1/search?q=Scaling')->assertOk()->assertJsonPath('posts.data.0.slug', 'scaling-web-apps');
        $this->getJson('/api/v1/search/suggest?q=Sc')->assertOk()->assertJsonPath('posts.0.slug', 'scaling-web-apps');
        $this->getJson('/api/v1/about')->assertOk()->assertJsonPath('stats.articles_count', 1);
    }

    public function test_drafts_are_hidden_from_public_api(): void
    {
        $this->post->update(['status' => 'draft']);

        $this->getJson('/api/v1/posts')->assertOk()->assertJsonCount(0, 'data');
        $this->getJson('/api/v1/posts/scaling-web-apps')->assertNotFound();
    }

    public function test_forms_contact_newsletter_and_guest_comment(): void
    {
        $this->postJson('/api/v1/contact', ['name' => 'A', 'email' => 'a@test.com', 'subject' => 'Hi', 'message' => 'Hello there team'])
            ->assertCreated();
        $this->postJson('/api/v1/newsletter/subscribe', ['email' => 'sub@test.com'])->assertOk()->assertJsonPath('subscribed', true);

        $this->postJson("/api/v1/posts/{$this->post->id}/comments", ['content' => 'Nice'])
            ->assertStatus(422)->assertJsonValidationErrors(['name', 'email']);

        $this->postJson("/api/v1/posts/{$this->post->id}/comments", ['content' => 'Nice post', 'name' => 'Guest', 'email' => 'g@test.com'])
            ->assertCreated()->assertJsonPath('data.author_name', 'Guest');

        $this->getJson("/api/v1/posts/{$this->post->id}/comments")->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_like_bookmark_follow_and_me_lists(): void
    {
        Sanctum::actingAs($this->reader);

        $this->postJson("/api/v1/posts/{$this->post->id}/like")->assertOk()->assertJson(['liked' => true, 'likes_count' => 1]);
        $this->postJson("/api/v1/posts/{$this->post->id}/bookmark")->assertOk()->assertJson(['bookmarked' => true]);
        $this->postJson('/api/v1/authors/' . $this->author->username . '/follow')->assertOk()->assertJson(['following' => true, 'followers_count' => 1]);
        $this->postJson('/api/v1/authors/' . $this->reader->username . '/follow')->assertStatus(422);

        // State flags show up on public endpoints when the token is sent.
        $this->getJson('/api/v1/posts')->assertJsonPath('data.0.liked_by_me', true)->assertJsonPath('data.0.bookmarked_by_me', true);
        $this->getJson('/api/v1/authors/' . $this->author->username)->assertJsonPath('data.is_followed_by_me', true);

        $this->postJson("/api/v1/posts/{$this->post->id}/comments", ['content' => 'Logged in comment'])
            ->assertCreated()->assertJsonPath('data.user_id', $this->reader->id);

        $this->getJson('/api/v1/me/bookmarks')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/me/likes')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/me/following')->assertOk()->assertJsonPath('data.0.id', $this->author->id)->assertJsonPath('data.0.is_followed_by_me', true);
        $this->getJson('/api/v1/me/comments')->assertOk()->assertJsonCount(1, 'data');

        Sanctum::actingAs($this->author);
        $this->getJson('/api/v1/me/followers')->assertOk()->assertJsonPath('data.0.id', $this->reader->id)->assertJsonPath('data.0.is_followed_by_me', false);
    }

    public function test_profile_update_with_image_and_password_change(): void
    {
        Storage::fake('public');
        Sanctum::actingAs($this->reader);

        $this->post('/api/v1/me/profile', [
            'name' => 'Reader Renamed',
            'bio' => 'Hello',
            'github' => 'https://github.com/example',
            'profile_image' => UploadedFile::fake()->create('me.jpg', 20, 'image/jpeg'),
        ], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonPath('user.name', 'Reader Renamed')
            ->assertJsonPath('user.social_links.github', 'https://github.com/example');

        $this->assertStringStartsWith('http', $this->reader->fresh()->profile_image === null ? 'x' : url($this->reader->fresh()->profile_image));

        $this->putJson('/api/v1/me/password', ['current_password' => 'wrong', 'password' => 'newpass123', 'password_confirmation' => 'newpass123'])
            ->assertStatus(422);
    }

    public function test_password_change_with_real_token(): void
    {
        $token = $this->reader->createToken('phone')->plainTextToken;
        $this->reader->createToken('tablet');

        $this->withToken($token)->putJson('/api/v1/me/password', [
            'current_password' => 'password',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ])->assertOk();

        $this->assertSame(1, $this->reader->tokens()->count());
        $this->assertTrue(Hash::check('newpass123', $this->reader->fresh()->password));
    }

    public function test_reader_cannot_reach_author_or_admin_endpoints(): void
    {
        Sanctum::actingAs($this->reader);

        $this->getJson('/api/v1/admin/dashboard')->assertForbidden();
        $this->getJson('/api/v1/admin/posts')->assertForbidden();
        $this->getJson('/api/v1/admin/users')->assertForbidden();
    }

    public function test_author_manages_own_posts_only(): void
    {
        Storage::fake('public');
        Sanctum::actingAs($this->author);

        $this->getJson('/api/v1/admin/dashboard')->assertOk()->assertJsonPath('stats.total_posts', 1);
        $this->getJson('/api/v1/admin/posts/form-options')->assertOk()->assertJsonStructure(['categories', 'tags', 'authors', 'statuses']);

        $id = $this->post('/api/v1/admin/posts', [
            'title' => 'Mobile Post',
            'content' => str_repeat('Content written from the Flutter app. ', 5),
            'category_id' => $this->category->id,
            'status' => 'published',
            'new_tags' => 'Flutter, Dart',
            'featured_image' => UploadedFile::fake()->create('cover.jpg', 20, 'image/jpeg'),
        ], ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'mobile-post')
            ->assertJsonCount(2, 'data.tags')
            ->json('data.id');

        $this->assertStringStartsWith('http', Post::find($id)->featured_image ? url(Post::find($id)->featured_image) : '');

        $this->putJson("/api/v1/admin/posts/{$id}", [
            'title' => 'Mobile Post Edited',
            'content' => str_repeat('Edited content from the Flutter app. ', 5),
            'category_id' => $this->category->id,
            'status' => 'draft',
        ])->assertOk()->assertJsonPath('data.title', 'Mobile Post Edited');

        $this->getJson('/api/v1/admin/posts')->assertOk()->assertJsonCount(2, 'data');

        $other = Post::create([
            'user_id' => $this->admin->id, 'category_id' => $this->category->id, 'title' => 'Admin Post',
            'slug' => 'admin-post', 'content' => 'x', 'status' => 'draft',
        ]);
        $this->getJson("/api/v1/admin/posts/{$other->id}")->assertForbidden();
        $this->deleteJson("/api/v1/admin/posts/{$other->id}")->assertForbidden();

        $this->deleteJson("/api/v1/admin/posts/{$id}")->assertOk();
        $this->getJson('/api/v1/admin/categories')->assertForbidden();
    }

    public function test_admin_endpoints(): void
    {
        Sanctum::actingAs($this->admin);

        $this->getJson('/api/v1/admin/dashboard')->assertOk()->assertJsonPath('role', 'admin');

        $catId = $this->postJson('/api/v1/admin/categories', ['name' => 'Mobile'])->assertCreated()->json('data.id');
        $this->putJson("/api/v1/admin/categories/{$catId}", ['name' => 'Mobile Dev'])->assertOk()->assertJsonPath('data.slug', 'mobile-dev');
        $this->deleteJson("/api/v1/admin/categories/{$this->category->id}")->assertStatus(409);
        $this->deleteJson("/api/v1/admin/categories/{$catId}")->assertOk();

        $tagId = $this->postJson('/api/v1/admin/tags', ['name' => 'Dart'])->assertCreated()->json('data.id');
        $this->getJson('/api/v1/admin/tags')->assertOk()->assertJsonStructure(['data', 'meta']);
        $this->deleteJson("/api/v1/admin/tags/{$tagId}")->assertOk();

        $this->getJson('/api/v1/admin/users?role=author')->assertOk()->assertJsonPath('data.0.email', 'author@test.com');
        $this->reader->createToken('phone');
        $this->patchJson("/api/v1/admin/users/{$this->reader->id}/toggle-status")->assertOk()->assertJsonPath('data.is_active', false);
        $this->assertSame(0, $this->reader->tokens()->count());
        $this->patchJson("/api/v1/admin/users/{$this->admin->id}/toggle-status")->assertStatus(422);
        $this->putJson("/api/v1/admin/users/{$this->reader->id}", ['name' => 'R', 'email' => 'reader@test.com', 'role' => 'author', 'is_active' => true])
            ->assertOk()->assertJsonPath('data.role', 'author');
        $this->deleteJson("/api/v1/admin/users/{$this->author->id}")->assertStatus(422);
        $this->deleteJson("/api/v1/admin/users/{$this->reader->id}")->assertOk();

        $comment = Comment::create(['post_id' => $this->post->id, 'name' => 'G', 'email' => 'g@test.com', 'content' => 'Pending one', 'status' => 'pending']);
        $this->getJson('/api/v1/admin/comments?status=pending')->assertOk()->assertJsonPath('counts.pending', 1)->assertJsonPath('data.0.email', 'g@test.com');
        $this->patchJson("/api/v1/admin/comments/{$comment->id}/approve")->assertOk()->assertJsonPath('data.status', 'approved');
        $this->patchJson("/api/v1/admin/comments/{$comment->id}/reject")->assertOk();
        $this->deleteJson("/api/v1/admin/comments/{$comment->id}")->assertOk();

        $message = ContactMessage::create(['name' => 'A', 'email' => 'a@test.com', 'subject' => 'S', 'message' => 'Message body']);
        $this->getJson('/api/v1/admin/messages')->assertOk()->assertJsonPath('unread_count', 1);
        $this->getJson("/api/v1/admin/messages/{$message->id}")->assertOk()->assertJsonPath('data.is_read', true);
        $this->patchJson("/api/v1/admin/messages/{$message->id}/read")->assertOk()->assertJsonPath('data.is_read', false);
        $this->deleteJson("/api/v1/admin/messages/{$message->id}")->assertOk();

        $this->postJson('/api/v1/newsletter/subscribe', ['email' => 'sub@test.com']);
        $subId = $this->getJson('/api/v1/admin/subscribers')->assertOk()->assertJsonPath('counts.all', 1)->json('data.0.id');
        $this->deleteJson("/api/v1/admin/subscribers/{$subId}")->assertOk();
    }
}
