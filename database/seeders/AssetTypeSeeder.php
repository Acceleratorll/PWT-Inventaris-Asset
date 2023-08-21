<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'Monitor',
                'isMoveable' => 0,
            ],
            [
                'name' => 'Printer',
                'isMoveable' => 0,
            ],
            [
                'name' => 'Kursi',
                'isMoveable' => 1,
            ],
        ];

        DB::table('asset_types')->insert($datas);
    }
}
