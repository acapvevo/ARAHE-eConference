<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locality')->insertTs([
            'code' => 'L',
            'name' => 'Local',
            'currency' => 'RM',
            'stripe_currency' => 'myr'
        ]);

        DB::table('locality')->insertTs([
            'code' => 'I',
            'name' => 'International',
            'currency' => 'USD$',
            'stripe_currency' => 'usd'
        ]);
    }
}
