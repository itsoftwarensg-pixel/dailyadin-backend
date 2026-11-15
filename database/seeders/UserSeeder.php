<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'bogdadishahali@gmail.com',
                'role_id' => 1,
                'phone' => '01743217299',
                'password' => Hash::make('admin$321'),
                'status' => 1,
                "created_at" => now(),
                "updated_at"=> now(),
            ],
            [
                'name' => 'Editor',
                'email' => 'admin@bluemapper.com',
                'role_id' => 2,
                'phone' => '01743217299',
                'password' => Hash::make('admin$321'),
                'status' => 1,
                "created_at" => now(),
                "updated_at"=> now(),
            ]
        ];
        DB::table('users')->insert($data);
    }
}
