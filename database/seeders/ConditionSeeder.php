<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    public function run(): void
    {
        $datas = [
            [
                'name' => 'Good',
            ],
            [
                'name' => 'Bad',
            ],
        ];

        DB::table('conditions')->insert($datas);
    }
}
