<?php

namespace Database\Seeders;

use Faker\Generator;
use App\Models\Participant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);

        Participant::create([
            'name' => 'reviewer1',
            'email' => 'reviewer1@gmail.com',
            'password' => Hash::make('reviewer1'),
            'telephoneNumber' => $faker->numerify('01#-#######'),
        ]);

        Participant::create([
            'name' => 'participant1',
            'email' => 'participant1@gmail.com',
            'password' => Hash::make('participant1'),
            'telephoneNumber' => $faker->numerify('01#-#######'),
        ]);
    }
}
