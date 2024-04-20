<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        DB::table('users')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'Admin',
                    'email' => 'admin@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'department_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'name' => 'Anak IT',
                    'email' => 'staffit@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'department_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'name' => 'Atasannya IT',
                    'email' => 'supervisorit@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'department_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 4,
                    'name' => 'Anak HRD',
                    'email' => 'staffhrd@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'department_id' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 5,
                    'name' => 'Atasannya HRD',
                    'email' => 'supervisorhrd@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'department_id' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
