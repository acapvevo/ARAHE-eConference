<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')->insertTs([
            'label' => 'Not Registered',
            'code' => 'NR',
            'description' => 'You did not registered for this congress. Please do the registration first',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Registered',
            'code' => 'WR',
            'description' => 'Your registration is currently waiting for approval from our admins',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Registered',
            'code' => 'DR',
            'description' => 'Your registration has been approved. You has been registered for this congress as ',
        ]);

        DB::table('status')->insertTs([
            'label' => 'No Submission',
            'code' => 'N',
            'description' => 'Waiting Participant to submit their paper',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Pending',
            'code' => 'P',
            'description' => 'Your submission currently pending to be appointed to Reviewer by Admin',
        ]);

        DB::table('status')->insertTs([
            'label' => 'In Review',
            'code' => 'IR',
            'description' => 'Your submission already appointed to a Reviewer and waiting for review',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Need Correction',
            'code' => 'C',
            'description' => 'Your submission need to do some correction based on comment given by Reviewer',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Waiting for Payment',
            'code' => 'WP',
            'description' => 'You need to settle the payment to join the main event',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Rejected',
            'code' => 'R',
            'description' => 'Sorry, your submission has been rejected',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Accepted',
            'code' => 'A',
            'description' => 'Congratulation, your submission has been accepted to the main event',
        ]);
    }
}
