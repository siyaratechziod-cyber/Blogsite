# BlogHub Mobile API (v1)

Base URL: `{APP_URL}/api/v1`. Every response is JSON, errors included.

## Auth

Login or register returns a Sanctum token. Send it on every request:

```
Authorization: Bearer <token>
Accept: application/json
```

Public endpoints work without a token. When a token is sent they also fill `liked_by_me`, `bookmarked_by_me` and `is_followed_by_me`.

| Method | Path | Body / notes |
|---|---|---|
| POST | `/auth/register` | `name, email, password, password_confirmation, terms=true`, optional `username, role (reader/author), device_name` |
| POST | `/auth/login` | `email, password`, optional `device_name` |
| POST | `/auth/forgot-password` | `email` (sends the reset email) |
| POST | `/auth/reset-password` | `token, email, password, password_confirmation` |
| GET | `/auth/me` | token required |
| POST | `/auth/logout` | revokes the current token |
| POST | `/auth/logout-all` | revokes every token for the user |

Status codes the app should handle: `401` missing/expired token, `403` deactivated account or wrong role, `404` not found, `409` conflict (e.g. category still has posts), `422` validation (`{message, errors: {field: [..]}}`), `429` rate limit.

## Public content

| Method | Path | Query / notes |
|---|---|---|
| GET | `/home` | featured, trending, latest, categories, featured_authors, testimonials, stats |
| GET | `/posts` | `q, category (slug), author (id), tag (slug), sort (latest/oldest/popular), page, per_page` |
| GET | `/posts/{slug}` | full post with `content`, approved `comments`, plus `related` (increments views) |
| GET | `/posts/{id}/comments` | paginated approved comments |
| POST | `/posts/{id}/comments` | `content`; guests also send `name, email` |
| GET | `/categories` | all categories with `posts_count` |
| GET | `/categories/{slug}` | paginated posts + `category` |
| GET | `/tags` | `limit` (default 30) |
| GET | `/authors` | `q, specialty, page, per_page`; also returns `specialties` |
| GET | `/authors/{username}` | author profile + `top_tags` |
| GET | `/authors/{username}/posts` | paginated |
| GET | `/search` | `q` |
| GET | `/search/suggest` | `q` (min 2 chars) |
| GET | `/about` | team + stats |
| POST | `/contact` | `name, email, subject, message` |
| POST | `/newsletter/subscribe` | `email` |

## Logged-in user

| Method | Path | Notes |
|---|---|---|
| POST | `/posts/{id}/like` | toggle, returns `liked, likes_count` |
| POST | `/posts/{id}/bookmark` | toggle, returns `bookmarked` |
| POST | `/authors/{username}/follow` | toggle, returns `following, followers_count` |
| POST | `/me/profile` | multipart: `name, bio, specialty, twitter, github, linkedin, profile_image, cover_image, remove_profile_image, remove_cover_image` |
| PUT | `/me/password` | `current_password, password, password_confirmation` (signs out other devices) |
| GET | `/me/bookmarks`, `/me/likes`, `/me/following`, `/me/followers`, `/me/comments` | paginated |

## Author and admin panel

Authors see and edit only their own posts. Admins see everything. In these routes `{id}` is always the numeric id.

| Method | Path | Role |
|---|---|---|
| GET | `/admin/dashboard` | author, admin |
| GET | `/admin/posts/form-options` | author, admin |
| GET | `/admin/posts` (`q, status, category, author, page`) | author, admin |
| POST | `/admin/posts` | author, admin |
| GET / POST / PUT / DELETE | `/admin/posts/{id}` | author (own), admin |
| CRUD | `/admin/categories`, `/admin/tags` | admin |
| GET | `/admin/users` (`q, role, status`) | admin |
| GET / PUT / DELETE | `/admin/users/{id}` | admin |
| PATCH | `/admin/users/{id}/toggle-status` | admin |
| GET | `/admin/comments` (`status, q`) | admin |
| PATCH | `/admin/comments/{id}/approve`, `/admin/comments/{id}/reject` | admin |
| DELETE | `/admin/comments/{id}` | admin |
| GET / DELETE | `/admin/messages`, `/admin/messages/{id}` | admin |
| PATCH | `/admin/messages/{id}/read` | admin |
| GET / DELETE | `/admin/subscribers`, `/admin/subscribers/{id}` | admin |

Post create/update body: `title, content (min 50 chars), category_id, status (draft/published)`, optional `slug, excerpt, tags[] (ids), new_tags ("Flutter, Dart"), reading_time, published_at, featured_image (file), remove_featured_image, user_id (admin only)`.

**File uploads:** PHP does not parse multipart bodies on PUT, so send uploads as `POST` with `multipart/form-data` to `/admin/posts/{id}` and `/me/profile`. JSON-only edits can use PUT.

## Pagination

List endpoints return Laravel's standard shape:

```json
{ "data": [ ... ], "links": { "next": "..." }, "meta": { "current_page": 1, "last_page": 4, "per_page": 9, "total": 31 } }
```

Use `?page=` for infinite scroll and stop when `links.next` is null. `per_page` is capped at 50.

## Images

`featured_image`, `avatar_url`, `cover_image` are absolute URLs built from `APP_URL`. Set `APP_URL` to an address the phone can reach (e.g. `http://10.0.2.2:8000` for the Android emulator, or your LAN IP for a real device) and run `php artisan storage:link`.
