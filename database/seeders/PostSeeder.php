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
            [
                'title' => 'The Cortisol Connection: Stress and PCOS',
                'excerpt' => 'How chronic stress worsens PCOS symptoms and what I do to actually wind down.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/reading.PNG',
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Cycle Syncing 101: Matching Life to Your Hormones',
                'excerpt' => 'A beginner-friendly intro to adjusting food, exercise, and rest around your cycle phases.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/thinking.PNG',
                'published_at' => now()->subDays(18),
            ],
            [
                'title' => 'Inositol: Six Months In, Here Is What Changed',
                'excerpt' => 'An honest review of taking myo-inositol daily — what improved, what stayed the same.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/standing.PNG',
                'published_at' => now()->subDays(22),
            ],
            [
                'title' => 'Sleep and PCOS: The Habit That Moved the Needle Most',
                'excerpt' => 'Why fixing my sleep routine had a bigger impact than any supplement I tried.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/waving.PNG',
                'published_at' => now()->subDays(28),
            ],
            [
                'title' => 'What My Blood Work Actually Showed',
                'excerpt' => 'Breaking down my latest results in plain language and what I am adjusting because of them.',
                'content' => 'This is a starter post body. Replace with your full article content.',
                'cover_image' => 'images/tired.PNG',
                'published_at' => now()->subDays(33),
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
