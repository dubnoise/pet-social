<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $post = Post::create([
            'user_id' => Auth::id(),
            'pet_id' => $request->pet_id,
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        // 🏆 Comprobar y otorgar insignias a la mascota (ej: Primer Latido)
        \App\Http\Controllers\PetController::checkAndAwardBadges($post->pet);

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
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Publicación eliminada correctamente 🐾');
    }

    public function like(Request $request, Post $post): JsonResponse|RedirectResponse
    {
        $userId = Auth::id();

        // Buscamos si ya existe un like de este usuario para este post
        $existingLike = $post->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            // Si existe, lo eliminamos (dislike)
            $existingLike->delete();
            $liked = false;
        } else {
            // Si no existe, lo creamos de forma limpia
            $post->likes()->create([
                'user_id' => $userId,
            ]);
            $liked = true;
        }

        // Refrescamos la relación para contar el total exacto desde la base de datos
        $post->load('likes');
        $likesCount = $post->likes->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
            ]);
        }

        return back();
    }
}
