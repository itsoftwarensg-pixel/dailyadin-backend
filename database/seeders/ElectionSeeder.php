<?php

namespace Database\Seeders;

use App\Models\Election;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Election::create([
            'title' => 'National Election 2026',
            'name' => 'national_election_2026',
            'slug' => 'election-dashboard-2026',
            'data' => json_encode([
                'referendum' => [
                    'title_bn' => 'গণভোট',
                    'title_en' => 'Referendum',
                    'items' => [
                        ['key'=>'yes','label_bn'=>'হ্যাঁ','label_en'=>'Yes','value'=>30],
                        ['key'=>'no','label_bn'=>'না','label_en'=>'No','value'=>70]
                    ]
                ],
                'result' => [
                    'title_bn' => 'ফলাফল',
                    'title_en' => 'Result',
                    'items' => [
                        ['key'=>'bnp','label_bn'=>'বিএনপি','label_en'=>'BNP','value'=>12],
                        ['key'=>'jamaat','label_bn'=>'জামায়াত','label_en'=>'Jamaat','value'=>190],
                        ['key'=>'ncp','label_bn'=>'এনসিপি','label_en'=>'NCP','value'=>15],
                        ['key'=>'independent','label_bn'=>'স্বতন্ত্র','label_en'=>'Independent','value'=>10],
                        ['key'=>'others','label_bn'=>'অন্যান্য','label_en'=>'Others','value'=>3]
                    ]
                ]
            ]),
            'status' => 1
        ]);
    }
}
