<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scale')->insertTs([
            'label' => 'Very Bad',
            'code' => 'VB',
            'mark' => 1,
        ]);

        DB::table('scale')->insertTs([
            'label' => 'Bad',
            'code' => 'B',
            'mark' => 2,
        ]);

        DB::table('scale')->insertTs([
            'label' => 'Average',
            'code' => 'A',
            'mark' => 3,
        ]);

        DB::table('scale')->insertTs([
            'label' => 'Good',
            'code' => 'G',
            'mark' => 4,
        ]);

        DB::table('scale')->insertTs([
            'label' => 'Very Good',
            'code' => 'GD',
            'mark' => 5,
        ]);
    }
}
