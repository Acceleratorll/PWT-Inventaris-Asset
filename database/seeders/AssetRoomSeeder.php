<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetRoomSeeder extends Seeder
{
    public function run(): void
    {
        $datas = [
            [
                'asset_id' => 1,
                'room_id' => 1,
                'qty' => 100,
            ],
        ];

        DB::table('asset_room')->insert($datas);
    }
}
