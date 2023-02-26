<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParticipantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('participant_type')->insertTs([
            'code' => 'KS',
            'name' => 'Keynote Speaker',
        ]);

        DB::table('participant_type')->insertTs([
            'code' => 'SS',
            'name' => 'Symposium Speaker',
        ]);

        DB::table('participant_type')->insertTs([
            'code' => 'OP',
            'name' => 'Oral Presenter',
        ]);

        DB::table('participant_type')->insertTs([
            'code' => 'PP',
            'name' => 'Poster Presenter',
        ]);

        DB::table('participant_type')->insertTs([
            'code' => 'WC',
            'name' => 'Workshop Co-ordinator',
        ]);

        DB::table('participant_type')->insertTs([
            'code' => 'A',
            'name' => 'ARAHE/IFHE EC Meeting ',
        ]);

        DB::table('participant_type')->insertTs([
            'code' => 'CP',
            'name' => 'Chair Person',
        ]);

        DB::table('participant_type')->insertTs([
            'code' => 'P',
            'name' => 'Participant',
        ]);

        DB::table('participant_type')->insertTs([
            'code' => 'OS',
            'name' => 'Organising Secreteriat',
        ]);
    }
}
