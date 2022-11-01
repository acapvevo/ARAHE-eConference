<?php

namespace Database\Seeders;

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
        Participant::create([
            'name' => 'reviewer1',
            'password' => Hash::make('reviewer1'),
            'email' => 'reviewer1@gmail.com'
        ]);

        Participant::create([
            'name' => 'participant1',
            'password' => Hash::make('participant1'),
            'email' => 'participant1@gmail.com'
        ]);
    }
}
