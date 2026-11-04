<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $request->validate([
            'pet_id' => ['required', 'exists:pets,id'],
            'content' => ['required', 'string', 'max:300'],
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'pet_id' => $request->pet_id,
            'post_id' => $post->id,
            'content' => $request->content,
        ]);

        return back()->with('success', '¡Comentario publicado! 🐾');
    }
}
