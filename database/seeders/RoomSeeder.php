<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $datas = [
            ['name' => 'Lelang', 'location' => 'Lelang'],
            ['name' => 'Musnahkan', 'location' => 'Musnahkan'],
            ['name' => 'Gudang', 'location' => 'Ji-U-Di-E-En-Ji'],
            ['name' => 'IT', 'location' => 'Kiri Atas'],
            ['name' => 'Keuangan', 'location' => 'Kiri Bawah'],
            ['name' => 'SDM Umum', 'location' => 'Kiri Kotak'],
        ];

        DB::table('rooms')->insert($datas);
    }
}
