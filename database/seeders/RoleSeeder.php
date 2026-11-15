<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
            [
                'name' => 'Desk',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
            [
                'name' => 'Editor',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
            [
                'name' => 'Reporter',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
            [
                'name' => 'Photographer',
                "created_at" => now(),
                "updated_at"=> now(),
            ],
        ];
        DB::table('roles')->insert($data);
    }
}
