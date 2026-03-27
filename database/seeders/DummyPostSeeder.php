<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DummyPostSeeder extends Seeder
{
    /**
     * Seed 5 predictable dummy posts for UI testing.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            Post::query()->updateOrCreate(
                ['slug' => 'dummy-post-'.$i],
                [
                    'title' => 'Dummy Test Post '.$i,
                    'excerpt' => 'This is a seeded dummy post for testing comments and interactions.',
                    'content' => "Dummy content block {$i}.\n\nUse this post to test replies, likes, and async comment actions.",
                    'category' => 'Testing',
                    'published_at' => now()->subDays(6 - $i),
                ]
            );
        }
    }
}
