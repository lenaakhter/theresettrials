<?php

namespace Database\Seeders;

use App\Models\Experiment;
use App\Models\ExperimentEntry;
use Illuminate\Database\Seeder;

class ExperimentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $experiments = [
            [
                'title' => 'Low-Carb Diet Challenge',
                'description' => 'Testing how a low-carb approach affects my insulin levels, energy, and PCOS symptoms over 12 weeks.',
                'category' => 'Nutrition',
                'start_date' => now()->subMonths(2),
                'status' => 'active',
                'entries' => [
                    [
                        'type' => 'observation',
                        'content' => 'Started tracking carb intake daily. Initial measurements: fasting glucose 105 mg/dL, energy level 6/10.',
                        'entry_date' => now()->subMonths(2),
                    ],
                    [
                        'type' => 'update',
                        'content' => 'End of week 1: Lost 2.5 lbs, energy improved to 7/10, less afternoon fatigue. Blood sugar more stable.',
                        'entry_date' => now()->subMonths(2)->addDays(7),
                    ],
                    [
                        'type' => 'result',
                        'content' => 'After 4 weeks: Fasting glucose down to 98 mg/dL, sustained energy levels throughout day, acne improved.',
                        'entry_date' => now()->subMonths(2)->addDays(28),
                    ],
                    [
                        'type' => 'note',
                        'content' => 'Social events challenging - had to bring own snacks. Missing bread, but effect on symptoms worth it.',
                        'entry_date' => now()->subMonths(2)->addDays(35),
                    ],
                    [
                        'type' => 'update',
                        'content' => '8 weeks in: Weight loss plateaued at 7 lbs total, but feeling best in years. Hormones more regular.',
                        'entry_date' => now()->subMonths(1)->addDays(8),
                    ],
                ]
            ],
            [
                'title' => 'Supplement Stack Experiment',
                'description' => 'Evaluating a combination of inositol, spearmint tea, and vitamin D to manage PCOS hormones.',
                'category' => 'Supplements',
                'start_date' => now()->subMonths(1),
                'status' => 'active',
                'entries' => [
                    [
                        'type' => 'observation',
                        'content' => 'Started with myo-inositol 2g/day, D-chiro-inositol, 2 cups spearmint tea daily, and vitamin D 4000 IU.',
                        'entry_date' => now()->subMonths(1),
                    ],
                    [
                        'type' => 'update',
                        'content' => 'Week 1: Slight nausea from inositol, adjusted with food. Spearmint tea is pleasant.',
                        'entry_date' => now()->subMonths(1)->addDays(7),
                    ],
                    [
                        'type' => 'result',
                        'content' => 'Week 3: Nausea resolved, hair fall reduced noticeably, skin clearer. Feeling more energetic.',
                        'entry_date' => now()->subMonths(1)->addDays(21),
                    ],
                    [
                        'type' => 'note',
                        'content' => 'Cost is significant (~$60/month), but visible results. Need to assess long-term affordability.',
                        'entry_date' => now()->subMonths(1)->addDays(28),
                    ],
                ]
            ],
            [
                'title' => 'Strength Training for PCOS',
                'description' => 'Low-maintenance strength training 3x/week to improve insulin sensitivity and muscle tone.',
                'category' => 'Exercise',
                'start_date' => now()->subMonths(2)->addDays(15),
                'status' => 'active',
                'entries' => [
                    [
                        'type' => 'observation',
                        'content' => 'Starting 30-minute full-body strength sessions with dumbbells at home, 3x per week.',
                        'entry_date' => now()->subMonths(2)->addDays(15),
                    ],
                    [
                        'type' => 'update',
                        'content' => 'Week 2: Moderate soreness, but feeling stronger. Enjoying the low-time-commitment approach.',
                        'entry_date' => now()->subMonths(2)->addDays(22),
                    ],
                    [
                        'type' => 'result',
                        'content' => '4 weeks: Visible muscle definition, clothes fitting better, post-workout energy boost noticeable.',
                        'entry_date' => now()->subMonths(2)->addDays(43),
                    ],
                ]
            ],
        ];

        foreach ($experiments as $experimentData) {
            $entries = $experimentData['entries'];
            unset($experimentData['entries']);

            $experiment = Experiment::create($experimentData);

            foreach ($entries as $entryData) {
                ExperimentEntry::create([
                    'experiment_id' => $experiment->id,
                    ...$entryData,
                ]);
            }
        }
    }
}
