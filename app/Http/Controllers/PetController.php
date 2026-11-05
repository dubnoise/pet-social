<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Badge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    // Mostrar formulario para crear una nueva mascota
    public function create(): View
    {
        return view('pets.create');
    }

    // Guardar la nueva mascota en la base de datos
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'species' => ['required', 'string', 'in:dog,cat,other'],
            'breed' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:250'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $pet = $user->pets()->create([
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'bio' => $request->bio,
        ]);

        // 🏆 Otorgar insignia de Pionero al registrar la mascota
        self::checkAndAwardBadges($pet);

        return redirect()->route('dashboard')->with('success', '¡Nueva mascota añadida a la manada con éxito! 🐾');
    }

    // Ver el perfil público/privado de una mascota y sus posts
    public function show(Pet $pet): View
    {
        $pet->load([
            'posts' => fn($query) => $query->latest(),
            'posts.user',
            'posts.likes',
            'posts.comments.pet',
            'posts.comments.user',
            'followers',
            'following',
            'badges'
        ]);

        // Filtrar publicaciones que contienen imágenes para el álbum
        $galleryPhotos = $pet->posts()->whereNotNull('image')->latest()->get();

        // 🐾 Obtenemos las mascotas del usuario autenticado para el selector de comentarios y fichas
        $userPets = Auth::user()->pets;

        return view('pets.show', compact('pet', 'galleryPhotos', 'userPets'));
    }

    // Seguir o dejar de seguir a otra mascota
    public function follow(Request $request, Pet $pet): RedirectResponse
    {
        $followerPetId = $request->input('follower_pet_id');
        $followerPet = Pet::findOrFail($followerPetId);

        // Verificar que la mascota que actúa pertenezca al usuario autenticado
        if ($followerPet->user_id !== Auth::id()) {
            abort(403);
        }

        if ($followerPet->id === $pet->id) {
            return back()->with('error', 'Una mascota no puede seguirse a sí misma.');
        }

        // Alternar seguimiento (seguir / dejar de seguir)
        $followerPet->following()->toggle($pet->id);

        // 🏆 Comprobar logros de seguimiento
        self::checkAndAwardBadges($followerPet);

        return back();
    }

    /**
     * 🏆 Sistema de Comprobación y Asignación de Insignias (Fase 4)
     */
    public static function checkAndAwardBadges(Pet $pet)
    {
        $allBadges = Badge::all();

        foreach ($allBadges as $badge) {
            // Si la mascota ya tiene la insignia, saltar
            if ($pet->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            $unlock = false;

            if ($badge->type === 'pioneer') {
                $unlock = true;
            } elseif ($badge->type === 'posts') {
                if ($pet->posts()->count() >= $badge->target_count) {
                    $unlock = true;
                }
            } elseif ($badge->type === 'likes') {
                $totalLikes = $pet->posts()->withCount('likes')->get()->sum('likes_count');
                if ($totalLikes >= $badge->target_count) {
                    $unlock = true;
                }
            } elseif ($badge->type === 'follows') {
                if ($pet->following()->count() >= $badge->target_count) {
                    $unlock = true;
                }
            }

            if ($unlock) {
                $pet->badges()->attach($badge->id);
            }
        }
    }

    public function updateAvatar(Request $request, Pet $pet): RedirectResponse
    {
        // Verificar que la mascota pertenece al usuario autenticado
        if ($pet->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'], // Máx 2MB
        ]);

        if ($request->hasFile('avatar')) {
            // Borrar el avatar anterior si no es el por defecto
            if ($pet->avatar && Storage::disk('public')->exists($pet->avatar)) {
                Storage::disk('public')->delete($pet->avatar);
            }

            // Guardar la nueva imagen
            $path = $request->file('avatar')->store('avatars', 'public');

            $pet->update([
                'avatar' => $path
            ]);
        }

        return back()->with('success', '¡Foto de perfil de la mascota actualizada con éxito! 🐾');
    }
}
