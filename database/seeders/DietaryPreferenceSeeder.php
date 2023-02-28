<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DietaryPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dietary_preference')->insertTs([
            'code' => 'N',
            'name' => 'Normal',
        ]);

        DB::table('dietary_preference')->insertTs([
            'code' => 'LI',
            'name' => 'Lactose Intolerance',
        ]);

        DB::table('dietary_preference')->insertTs([
            'code' => 'V',
            'name' => 'Vegetarian or Vegans',
        ]);

        DB::table('dietary_preference')->insertTs([
            'code' => 'PA',
            'name' => 'Peanut Allergies',
        ]);

        DB::table('dietary_preference')->insertTs([
            'code' => 'D',
            'name' => 'Diabetic',
        ]);

        DB::table('dietary_preference')->insertTs([
            'code' => 'GF',
            'name' => 'Gluten Free',
        ]);

        DB::table('dietary_preference')->insertTs([
            'code' => 'K',
            'name' => 'Kosher',
        ]);
    }
}
