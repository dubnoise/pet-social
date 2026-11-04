<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Humano que lo añade
            $table->string('name'); // Nombre del sitio (Ej: Parque Central Canino)
            $table->string('category'); // park, cafe, vet, store
            $table->text('description')->nullable();
            $table->string('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spots');
    }
};
