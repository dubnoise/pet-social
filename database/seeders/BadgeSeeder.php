<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Pionero 🐾',
                'slug' => 'pioneer',
                'description' => 'Registrado oficialmente en la comunidad BarkSocial.',
                'icon' => '🌟',
                'type' => 'pioneer',
                'target_count' => 1,
            ],
            [
                'name' => 'Primer Latido 📢',
                'slug' => 'first-post',
                'description' => 'Ha publicado su primer mensaje en el feed.',
                'icon' => '📝',
                'type' => 'posts',
                'target_count' => 1,
            ],
            [
                'name' => 'Rey de los Me Guau ❤️',
                'slug' => 'popular-pet',
                'description' => 'Ha acumulado más de 5 "Me Guau" en sus publicaciones.',
                'icon' => '💖',
                'type' => 'likes',
                'target_count' => 5,
            ],
            [
                'name' => 'Socialité 🐕',
                'slug' => 'social-pet',
                'description' => 'Sigue a 3 o más amigos en su manada.',
                'icon' => '🐾',
                'type' => 'follows',
                'target_count' => 3,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
