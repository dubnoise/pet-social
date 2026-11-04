<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // El dueño que recibe la notificación
            $table->foreignId('pet_id')->nullable()->constrained()->onDelete('cascade'); // La mascota que recibe la notificación (opcional)
            $table->string('type'); // ej: 'follow', 'like', 'comment'
            $table->text('data'); // Texto descriptivo de la notificación
            $table->string('url')->nullable(); // Enlace para redirigir al hacer clic
            $table->timestamp('read_at')->nullable(); // Para saber si ya fue leída
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
