<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $species
 * @property string|null $breed
 * @method static \App\Models\Pet create(array $attributes = [])
 */

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'breed',
        'avatar',
        'bio',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function getSpeciesInSpanishAttribute(): string
    {
        return match ($this->species) {
            'dog' => 'Perro',
            'cat' => 'Gato',
            default => 'Otro',
        };
    }

    // Mascotas que este peludo sigue
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(Pet::class, 'follows', 'follower_pet_id', 'followed_pet_id')->withTimestamps();
    }

    // Mascotas que siguen a este peludo (Sus seguidores / Manada)
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(Pet::class, 'follows', 'followed_pet_id', 'follower_pet_id')->withTimestamps();
    }

    // Comprobar si esta mascota sigue a otra objetivo
    public function isFollowing(Pet $otherPet): bool
    {
        return $this->following()->where('followed_pet_id', $otherPet->id)->exists();
    }
}
