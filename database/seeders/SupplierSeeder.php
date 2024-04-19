<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert(
            [
                [
                    'id' => 1,
                    'nama' => 'PT. ABCDEFGH',
                    'alamat' => 'Suatu tempat di bumi',
                    'telepon' => '08234878987457',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'nama' => 'PT. XYZ',
                    'alamat' => 'pulau jawa',
                    'telepon' => '08986876',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'nama' => 'PT. BLABLABLA',
                    'alamat' => 'negara bekasi',
                    'telepon' => '08235651651',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
