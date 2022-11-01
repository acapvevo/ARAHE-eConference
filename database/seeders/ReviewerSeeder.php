<?php

namespace Database\Seeders;

use App\Models\Reviewer;
use App\Models\Participant;
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
        $participant = Participant::find(1);

        Reviewer::create([
            'participant_id' => $participant->id,
            'password' => $participant->password,
            'email' => $participant->email,
        ]);
    }
}
