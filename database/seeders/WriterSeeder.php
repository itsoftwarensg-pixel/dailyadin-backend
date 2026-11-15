<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WriterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'করিম আহমদ',
                'alias' => 'alias',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
            [
                'name' => 'চাঁদের বুড়ি ডেস্ক',
                'alias' => 'alias',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
            [
                'name' => 'কালীগঞ্জ (গাজীপুর) প্রতিনিধি',
                'alias' => 'alias',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
            [
                'name' => 'আনন্দ আড্ডা প্রতিবেদক',
                'alias' => 'alias',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
            [
                'name' => 'ভিনদেশ ডেস্ক',
                'alias' => 'alias',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
        ];
        DB::table('writers')->insert($data);
    }
}
