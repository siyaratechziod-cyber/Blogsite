<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BlogHubCoreTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $author;
    protected Category $category;
    protected Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->author = User::create([
            'name' => 'Author User',
            'email' => 'author@test.com',
            'password' => Hash::make('password'),
            'role' => 'author',
        ]);

        $this->category = Category::create([
            'name' => 'Web Development',
            'slug' => 'web-development',
            'icon' => 'bi-code-slash',
        ]);

        $this->post = Post::create([
            'user_id' => $this->author->id,
            'category_id' => $this->category->id,
            'title' => 'Scaling Web Apps',
            'slug' => 'scaling-web-apps',
            'excerpt' => 'An excerpt on scaling.',
            'content' => 'Full technical content on building resilient distributed systems.',
            'status' => 'published',
            'published_at' => now(),
            'views' => 100,
            'reading_time' => 5,
        ]);
    }

    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Blog');
    }

    public function test_blogs_index_returns_successful_response(): void
    {
        $response = $this->get('/blogs');
        $response->assertStatus(200);
        $response->assertSee('Scaling Web Apps');
    }

    public function test_single_blog_returns_successful_response(): void
    {
        $response = $this->get('/blogs/' . $this->post->slug);
        $response->assertStatus(200);
        $response->assertSee('Scaling Web Apps');
        $response->assertSee('Full technical content');
    }

    public function test_categories_index_returns_successful_response(): void
    {
        $response = $this->get('/categories');
        $response->assertStatus(200);
        $response->assertSee('Web Development');
    }

    public function test_authors_index_returns_successful_response(): void
    {
        $response = $this->get('/authors');
        $response->assertStatus(200);
        $response->assertSee('Author User');
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign In');
    }

    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Create My Account');
    }

    public function test_admin_dashboard_redirects_unauthenticated_user(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_admin_dashboard_forbidden_for_reader(): void
    {
        $reader = User::create([
            'name' => 'Reader',
            'email' => 'reader@test.com',
            'password' => Hash::make('password'),
            'role' => 'reader',
        ]);

        $response = $this->actingAs($reader)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_dashboard_accessible_by_admin(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }
}
