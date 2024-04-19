<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert(
            [
                [
                    'id' => 1,
                    'id_kategori' => 1,
                    'id_supplier' => 1,
                    'kode' => 'BRG.0001',
                    'nama' => 'Sanken Dispenser Galon Bawah',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'id_kategori' => 1,
                    'id_supplier' => 2,
                    'kode' => 'BRG.0002',
                    'nama' => 'LG AC Standart',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'id_kategori' => 3,
                    'id_supplier' => 2,
                    'kode' => 'BRG.0002',
                    'nama' => 'Homedoki Kursi Kantor',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
