<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BnContentTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = storage_path('bn_contents_tagids.csv');
        $handle = fopen($csvFile, 'r');

        if ($handle) {
            // Skip header
            fgetcsv($handle);

            $insertData = [];
            while (($data = fgetcsv($handle)) !== false) {
                $contentId = $data[0];
                $tagIds = array_filter(explode(',', $data[1]));

                foreach ($tagIds as $tagId) {
                    $insertData[] = [
                        'bn_content_id' => $contentId,
                        'tag_id'        => $tagId,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }

                if (count($insertData) >= 500) {
                    DB::table('bn_content_tag')->insert($insertData);
                    $insertData = [];
                }
            }

            if (!empty($insertData)) {
                DB::table('bn_content_tag')->insert($insertData);
            }

            fclose($handle);
            $this->command->info("✅ Migration to pivot table complete!");
        }
    }
}
