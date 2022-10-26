<?php

namespace Database\Seeders;

use App\Models\Reviewer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReviewerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Reviewer::create([
            'name' => 'reviewer1',
            'password' => Hash::make('reviewer1'),
            'email' => 'reviewer1@gmail.com'
        ]);
    }
}
