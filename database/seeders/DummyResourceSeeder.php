<?php

namespace Database\Seeders;

use App\Models\Experiment;
use App\Models\Post;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class DummyResourceSeeder extends Seeder
{
    /**
     * Seed dummy product resources linked to seeded posts and experiments.
     */
    public function run(): void
    {
        $dummyPosts = Post::query()
            ->where('slug', 'like', 'dummy-post-%')
            ->orderBy('slug')
            ->take(5)
            ->get();

        $experiments = Experiment::query()
            ->notArchived()
            ->orderBy('id')
            ->take(3)
            ->get();

        $postProducts = [
            [
                'name' => 'Myo-Inositol Powder',
                'image_url' => 'https://dummyimage.com/640x640/f8edf2/9f5e72&text=Myo+Inositol',
                'product_url' => 'https://www.iherb.com/search?kw=myo+inositol',
            ],
            [
                'name' => 'Spearmint Tea Bags',
                'image_url' => 'https://dummyimage.com/640x640/f0f4ea/6b8f53&text=Spearmint+Tea',
                'product_url' => 'https://www.iherb.com/search?kw=spearmint+tea',
            ],
            [
                'name' => 'Vitamin D3 Softgels',
                'image_url' => 'https://dummyimage.com/640x640/f7efe6/af7a40&text=Vitamin+D3',
                'product_url' => 'https://www.iherb.com/search?kw=vitamin+d3',
            ],
            [
                'name' => 'Magnesium Glycinate',
                'image_url' => 'https://dummyimage.com/640x640/edf3f8/5a7891&text=Magnesium',
                'product_url' => 'https://www.iherb.com/search?kw=magnesium+glycinate',
            ],
            [
                'name' => 'High Protein Greek Yogurt',
                'image_url' => 'https://dummyimage.com/640x640/faf1ec/b88666&text=Protein+Yogurt',
                'product_url' => 'https://www.google.com/search?q=high+protein+greek+yogurt',
            ],
        ];

        foreach ($dummyPosts as $index => $post) {
            $product = $postProducts[$index % count($postProducts)];

            Resource::query()->updateOrCreate(
                [
                    'name' => $product['name'],
                    'linkable_type' => Post::class,
                    'linkable_id' => $post->id,
                ],
                [
                    'image_url' => $product['image_url'],
                    'product_url' => $product['product_url'],
                ]
            );
        }

        $experimentProducts = [
            [
                'name' => 'Insulin-Friendly Meal Planner',
                'image_url' => 'https://dummyimage.com/640x640/eaf4ee/57836b&text=Meal+Planner',
                'product_url' => 'https://www.google.com/search?q=pcos+low+carb+meal+planner',
            ],
            [
                'name' => 'Cycle Tracking Journal',
                'image_url' => 'https://dummyimage.com/640x640/f3eaf3/8a5e8c&text=Cycle+Journal',
                'product_url' => 'https://www.google.com/search?q=cycle+tracking+journal',
            ],
            [
                'name' => 'Adjustable Dumbbells',
                'image_url' => 'https://dummyimage.com/640x640/edf0f6/5a6885&text=Dumbbells',
                'product_url' => 'https://www.google.com/search?q=adjustable+dumbbells',
            ],
        ];

        foreach ($experiments as $index => $experiment) {
            $product = $experimentProducts[$index % count($experimentProducts)];

            Resource::query()->updateOrCreate(
                [
                    'name' => $product['name'],
                    'linkable_type' => Experiment::class,
                    'linkable_id' => $experiment->id,
                ],
                [
                    'image_url' => $product['image_url'],
                    'product_url' => $product['product_url'],
                ]
            );
        }
    }
}
