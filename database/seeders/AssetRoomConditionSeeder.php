<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetRoomConditionSeeder extends Seeder
{
    public function run(): void
    {
        $datas = [
            [
                'asset_id' => 1,
                'room_id' => 3,
                'condition_id' => 1,
                'qty' => 50,
            ],
            [
                'asset_id' => 1,
                'room_id' => 3,
                'condition_id' => 2,
                'qty' => 50,
            ],
        ];

        DB::table('asset_room_condition')->insert($datas);
    }
}
