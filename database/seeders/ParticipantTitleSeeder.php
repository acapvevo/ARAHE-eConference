<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParticipantTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('participant_title')->insertTs([
            'code' => 'mr',
            'name' => 'Mr.',
        ]);

        DB::table('participant_title')->insertTs([
            'code' => 'miss',
            'name' => 'Miss',
        ]);

        DB::table('participant_title')->insertTs([
            'code' => 'mrs',
            'name' => 'Mrs.',
        ]);

        DB::table('participant_title')->insertTs([
            'code' => 'ms',
            'name' => 'Ms.',
        ]);

        DB::table('participant_title')->insertTs([
            'code' => 'dr',
            'name' => 'Dr.',
        ]);

        DB::table('participant_title')->insertTs([
            'code' => 'prof',
            'name' => 'Prof.',
        ]);
    }
}
