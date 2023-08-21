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
            ['name' => 'IT'],
            ['name' => 'Keuangan'],
            ['name' => 'SDM Umum'],
        ];

        DB::table('rooms')->insert($datas);
    }
}
