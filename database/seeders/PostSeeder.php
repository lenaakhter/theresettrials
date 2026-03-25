<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'What Actually Helped My PCOS Fatigue',
                'excerpt' => 'A realistic breakdown of small routine changes that improved my daily energy without crash diets.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/reading.PNG',
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Supplements I Tried (And What I Noticed)',
                'excerpt' => 'No hype, just notes on what I tested, what changed, and what did not make a difference.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/thinking.PNG',
                'published_at' => now()->subDays(9),
            ],
            [
                'title' => 'Building a PCOS-Friendly Grocery List',
                'excerpt' => 'My weekly shopping structure to make blood-sugar-friendly meals easier during busy weeks.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/standing.PNG',
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'How I Track Symptoms Without Overwhelm',
                'excerpt' => 'A simple template for spotting patterns in sleep, stress, food, cycle, and skin changes.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/waving.PNG',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'PCOS and Exercise: What Felt Sustainable',
                'excerpt' => 'Why lower-intensity consistency worked better for me than pushing hard and burning out.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/tired.PNG',
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($posts as $postData) {
            Post::updateOrCreate(
                ['slug' => Str::slug($postData['title'])],
                $postData
            );
        }
    }
}
