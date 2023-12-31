<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'asset_type_id' => 1,
                'item_code' => 'J3',
                'name' => 'SPI',
                'acquition' => '2023-08-19',
                'total' => 100,
                'last_move_date' => '2023-08-19',
                'note' => '',
            ],
        ];

        DB::table('assets')->insert($datas);
    }
}
