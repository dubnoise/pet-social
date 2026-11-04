<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(Request $request, Pet $pet): RedirectResponse
    {
        $request->validate([
            'follower_pet_id' => ['required', 'exists:pets,id'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tu mascota
        $followerPet = $user->pets()->findOrFail($request->follower_pet_id);

        // Evitar que tu mascota se siga a sí misma
        if ($followerPet->id === $pet->id) {
            return back()->with('error', 'Una mascota no puede seguirse a sí misma.');
        }

        // $pet es la mascota que se va a seguir (followed_pet_id)
        if ($followerPet->isFollowing($pet)) {
            $followerPet->following()->detach($pet->id);
            $message = "Has dejado de seguir a {$pet->name}.";
        } else {
            $followerPet->following()->attach($pet->id);
            $message = "¡Ahora sigues a {$pet->name}!";
        }

        return back()->with('success', $message);
    }
}
