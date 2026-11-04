<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Validar tanto datos del humano como de la mascota
        $request->validate([
            // Datos del Usuario
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Datos de la Mascota
            'pet_name' => ['required', 'string', 'max:100'],
            'pet_species' => ['required', 'string', 'in:dog,cat,other'],
            'pet_breed' => ['nullable', 'string', 'max:100'],
            'pet_bio' => ['nullable', 'string', 'max:250'],
        ]);

        // 2. Transacción para asegurar la creación atómica
        DB::transaction(function () use ($request, &$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->pets()->create([
                'name' => $request->pet_name,
                'species' => $request->pet_species,
                'breed' => $request->pet_breed,
                'bio' => $request->pet_bio,
            ]);
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
