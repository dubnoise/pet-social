<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SpotController extends Controller
{
    public function index(): View
    {
        $spots = Spot::with('user')->latest()->get();
        return view('spots.index', compact('spots'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:park,cafe,vet,store',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Spot::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('spots.index')->with('success', '¡Punto Pet-Friendly añadido con éxito 🐾!');
    }

    public function mySpots(): View
    {
        $spots = Spot::where('user_id', Auth::id())->latest()->get();
        return view('spots.my-spots', compact('spots'));
    }

    public function destroy(Spot $spot): RedirectResponse
    {
        // Verificar que el usuario sea el dueño del spot
        if ($spot->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $spot->delete();

        return back()->with('success', 'Lugar eliminado correctamente 🐾');
    }
}
