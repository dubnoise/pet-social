<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre (Ej: Estrella Guau)
            $table->string('slug')->unique(); // Identificador único (Ej: star-pet)
            $table->string('description'); // Descripción del logro
            $table->string('icon'); // Emoji o icono (Ej: ⭐)
            $table->string('type'); // posts, likes, follows, pioneer
            $table->integer('target_count')->default(1); // Cantidad necesaria para desbloquear
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
