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

        // Notificar al dueño de la publicación (siempre que no sea el mismo usuario)
        if ($post->user_id !== Auth::id()) {
            \App\Models\Notification::create([
                'user_id' => $post->user_id,
                'pet_id' => $post->pet_id,
                'type' => 'comment',
                'data' => Auth::user()->name . " ha comentado la publicación de {$post->pet->name} 💬",
                'url' => route('dashboard'),
            ]);
        }

        return back()->with('success', '¡Comentario publicado! 🐾');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        // Verificar que el usuario sea el autor del comentario
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $comment->delete();

        return back();
    }
}
