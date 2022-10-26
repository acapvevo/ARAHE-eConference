<?php

namespace Database\Seeders;

use App\Models\Manual;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ManualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manual::create([
            'name' => 'User Manual Admin',
            'guard' => 'admin',
        ]);

        Manual::create([
            'name' => 'User Manual Reviewer',
            'guard' => 'reviewer',
        ]);

        Manual::create([
            'name' => 'User Manual Participant',
            'guard' => 'participant',
        ]);
    }
}
