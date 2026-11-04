<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpotController;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Página de bienvenida pública
Route::get('/', function () {
    return view('welcome');
});

// Rutas protegidas para usuarios autenticados
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard principal gestionado por el controlador con el filtro de Manada
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Publicaciones (Posts)
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Interacciones (Likes y Comentarios)
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Rutas de Mascotas
    Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
    Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');

    // Sistema de Seguidores / Manada
    Route::post('/pets/{pet}/follow', [FollowController::class, 'toggle'])->name('pets.follow');

    // Sistema de Notificaciones: Marcar todas como leídas (CORREGIDO)
    Route::post('/notifications/read-all', function () {
        $user = User::find(Auth::id());
        if ($user) {
            $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        }
        return back();
    })->name('notifications.readAll');

    // Gestión del perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mapa Pet-Friendly
    Route::get('/spots', [SpotController::class, 'index'])->name('spots.index');
    Route::post('/spots', [SpotController::class, 'store'])->name('spots.store');
    Route::get('/my-spots', [SpotController::class, 'mySpots'])->name('spots.my-spots');
    Route::delete('/spots/{spot}', [SpotController::class, 'destroy'])->name('spots.destroy');

});

require __DIR__.'/auth.php';
