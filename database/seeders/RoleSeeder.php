<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'SPI'],
            ['name' => 'Logistik'],
            ['name' => 'SDM Umum'],
        ];

        DB::table('roles')->insert($datas);
    }
}
