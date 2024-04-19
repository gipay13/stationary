<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert(
            [
                [
                    'id' => 1,
                    'nama' => 'Elektronik',
                    'slug' => Str::slug('Elektronik'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'nama' => 'Alat Tulis Kantor',
                    'slug' => Str::slug('Alat Tulis Kantor'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'nama' => 'Perabotan',
                    'slug' => Str::slug('Perabotan'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 4,
                    'nama' => 'Alat Kebersihan',
                    'slug' => Str::slug('Alat Kebersihan'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
