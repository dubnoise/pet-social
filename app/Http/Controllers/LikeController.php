<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Post $post): RedirectResponse
    {
        $userId = Auth::id();
        $like = Like::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
        } else {
            Like::create([
                'user_id' => $userId,
                'post_id' => $post->id,
            ]);
            $post->increment('likes_count');

            // Crear notificación para el dueño del post (siempre que no sea el mismo usuario)
            if ($post->user_id !== $userId) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'pet_id' => $post->pet_id,
                    'type' => 'like',
                    'data' => "A " . Auth::user()->name . " le ha gustado la publicación de {$post->pet->name} ❤️",
                    'url' => route('dashboard'),
                ]);
            }
        }

        return back();
    }
}
