<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'IT', 'location' => 'Kiri Atas'],
            ['name' => 'Keuangan', 'location' => 'Kiri Bawah'],
            ['name' => 'SDM Umum', 'location' => 'Kiri Kotak'],
        ];

        DB::table('rooms')->insert($datas);
    }
}
