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
            ['name' => 'Monitor'],
            ['name' => 'Printer'],
            ['name' => 'Kursi'],
        ];

        DB::table('asset_types')->insert($datas);
    }
}
