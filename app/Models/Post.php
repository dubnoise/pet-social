<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $pet_id
 * @property string $content
 * @property string|null $image
 * @property int $likes_count
 * @method static \App\Models\Post create(array $attributes = [])
 * @method static \Illuminate\Database\Eloquent\Builder|static latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|static with(array|string $relations)
 */

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pet_id',
        'content',
        'image',
        'likes_count',
    ];

    // Relación con el Humano (Usuario)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la Mascota
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function isLikedByUser(?int $userId): bool
    {
        if (!$userId) return false;
        return $this->likes->contains('user_id', $userId);
    }
}
