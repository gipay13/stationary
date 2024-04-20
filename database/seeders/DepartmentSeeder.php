<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert(
            [
                [
                    'id' => 1,
                    'nama' => 'Information Technology Department',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'nama' => 'Human Resource Department',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
