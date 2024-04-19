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
        DB::table('roles')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'Admin',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'name' => 'Staff',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'name' => 'Supervisor',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
