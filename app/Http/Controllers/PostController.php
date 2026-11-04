<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with(['user', 'pet', 'likes', 'comments.pet', 'comments.user'])
            ->latest()
            ->get();

        /** @var User $user */
        $user = Auth::user();
        $userPets = $user->pets;

        return view('dashboard', compact('posts', 'userPets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pet_id' => ['required', 'exists:pets,id'],
            'content' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'pet_id' => $request->pet_id,
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return redirect()->route('dashboard')->with('success', '¡Ladrido publicado con éxito! 🐾');
    }

    public function destroy(Post $post): RedirectResponse
    {
        // Verificar que el usuario autenticado sea el creador de la publicación
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        // Si tiene imagen adjunta, la borramos del storage
        if ($post->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Publicación eliminada correctamente 🐾');
    }
}
