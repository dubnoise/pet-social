<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

        $user->pets()->create([
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'bio' => $request->bio,
        ]);

        return redirect()->route('dashboard')->with('success', '¡Nueva mascota añadida a la manada con éxito! 🐾');
    }

    // Ver el perfil público/privado de una mascota y sus posts
   public function show(Pet $pet)
    {
        // Cargar publicaciones con interacciones, seguidores y seguidos
        $pet->load([
            'posts' => fn($query) => $query->latest(),
            'posts.user',
            'posts.likes',
            'posts.comments.pet',
            'followers',
            'following'
        ]);

        return view('pets.show', compact('pet'));
    }
}
