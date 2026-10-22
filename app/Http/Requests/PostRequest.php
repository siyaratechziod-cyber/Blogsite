<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAuthor();
    }

    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($post?->id),
            ],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:50'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'remove_featured_image' => ['nullable', 'boolean'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'tags' => ['nullable', 'array', 'max:20'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'new_tags' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'reading_time' => ['nullable', 'integer', 'min:1', 'max:120'],
            'published_at' => ['nullable', 'date'],
            'user_id' => ['nullable', 'integer', Rule::exists('users', 'id')->whereIn('role', ['author', 'admin'])],
        ];
    }

    public function messages(): array
    {
        return [
            'featured_image.image' => 'The featured image must be a valid image file.',
            'featured_image.mimes' => 'Allowed image formats: JPG, JPEG, PNG, WEBP and GIF.',
            'featured_image.max' => 'The featured image may not be larger than 5 MB.',
            'content.min' => 'The content must be at least 50 characters long.',
            'category_id.required' => 'Please choose a category for this post.',
            'user_id.exists' => 'The selected author is not a valid author account.',
        ];
    }
}
