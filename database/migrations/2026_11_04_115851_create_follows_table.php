<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            // Mascota que sigue (Seguidor)
            $table->foreignId('follower_pet_id')->constrained('pets')->onDelete('cascade');
            // Mascota a la que siguen (Seguido)
            $table->foreignId('followed_pet_id')->constrained('pets')->onDelete('cascade');
            $table->timestamps();

            // Una mascota no puede seguir dos veces a la misma
            $table->unique(['follower_pet_id', 'followed_pet_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
