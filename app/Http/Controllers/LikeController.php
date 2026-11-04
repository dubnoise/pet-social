<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
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
        }

        return back();
    }
}
