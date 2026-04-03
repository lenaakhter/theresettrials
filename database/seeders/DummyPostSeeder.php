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
        $demoBlocks = [
            'version' => 1,
            'blocks' => [
                [
                    'type' => 'heading',
                    'level' => 2,
                    'text' => 'Why I built this post format',
                ],
                [
                    'type' => 'paragraph',
                    'text' => 'This is a demo post that shows headings, a generated contents list, and TikTok embeds. Edit this post in admin to test how each block type behaves.',
                ],
                [
                    'type' => 'heading',
                    'level' => 2,
                    'text' => 'Sample routine update',
                ],
                [
                    'type' => 'paragraph',
                    'text' => "I started small: more protein at breakfast, a 15-minute walk, and consistent sleep.\n\nThe goal is realistic consistency, not perfection.",
                ],
                [
                    'type' => 'heading',
                    'level' => 3,
                    'text' => 'TikTok embed example',
                ],
                [
                    'type' => 'tiktok',
                    'url' => 'https://www.tiktok.com/@tiktok/video/7217227049150655749',
                ],
                [
                    'type' => 'heading',
                    'level' => 2,
                    'text' => 'What to edit',
                ],
                [
                    'type' => 'paragraph',
                    'text' => 'Try adding a new heading and rearranging the blocks. The table of contents under the disclaimer will update automatically.',
                ],
            ],
        ];

        Post::query()->updateOrCreate(
            ['slug' => 'dummy-post-content-builder-demo'],
            [
                'title' => 'Demo Post: Contents, Headings, and TikTok Embed',
                'excerpt' => 'Use this post to test table of contents links, heading blocks, and TikTok embeds.',
                'content' => json_encode($demoBlocks, JSON_UNESCAPED_SLASHES),
                'category' => 'Testing',
                'published_at' => now()->subDay(),
            ]
        );

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
