<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $rules = [
            'content' => ['required', 'string', 'min:3', 'max:2000'],
        ];

        if (! Auth::check()) {
            $rules['name'] = ['required', 'string', 'max:100'];
            $rules['email'] = ['required', 'email', 'max:150'];
        }

        $validated = $request->validate($rules);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'name' => Auth::check() ? Auth::user()->name : $validated['name'],
            'email' => Auth::check() ? Auth::user()->email : $validated['email'],
            'content' => $validated['content'],
            'status' => 'approved',
        ]);

        return back()->with('success', 'Your comment has been posted successfully!');
    }
}
