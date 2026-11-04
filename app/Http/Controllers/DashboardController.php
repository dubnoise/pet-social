<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Obtener las mascotas del usuario
        $userPets = $user ? $user->pets : collect();
        $userPetIds = $userPets->pluck('id')->toArray();

        // Obtener el tipo de feed de la URL (?feed=manada o all)
        $feedType = $request->query('feed', 'all');

        $postsQuery = Post::with(['user', 'pet.followers', 'pet.following', 'likes', 'comments.pet', 'comments.user'])
            ->latest();

        // Si el usuario selecciona "Mi Manada"
        if ($feedType === 'manada') {
            if (!empty($userPetIds)) {
                // Buscar qué mascotas siguen las mascotas de este usuario
                $followedPetIds = DB::table('follows')
                    ->whereIn('follower_pet_id', $userPetIds)
                    ->pluck('followed_pet_id')
                    ->toArray();

                if (!empty($followedPetIds)) {
                    // Mostrar solo los posts de las mascotas seguidas
                    $postsQuery->whereIn('pet_id', $followedPetIds);
                } else {
                    // Si NO sigue a nadie, forzar que la consulta devuelva CERO resultados
                    $postsQuery->whereRaw('0 = 1');
                }
            } else {
                // Si el usuario no tiene mascotas, no hay manada -> CERO resultados
                $postsQuery->whereRaw('0 = 1');
            }
        }

        $posts = $postsQuery->get();

        return view('dashboard', compact('posts', 'userPets', 'feedType'));
    }
}
