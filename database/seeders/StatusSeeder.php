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
        //Registration Status
        DB::table('status')->insertTs([
            'label' => 'Not Registered',
            'code' => 'NR',
            'description' => 'You did not registered for this congress. Please do the registration first',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Waiting for Approval',
            'code' => 'WR',
            'description' => 'Your registration is currently waiting for approval from our admins',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Registered',
            'code' => 'DR',
            'description' => 'Your registration has been approved. You can proceed to choose package',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Registration Rejected',
            'code' => 'RR',
            'description' => 'Your registration has been rejected.',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Need to Upload Different Registration Proof',
            'code' => 'UR',
            'description' => 'Please reupload different proof for your registration',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Waiting for Payment From Participant',
            'code' => 'PR',
            'description' => 'Your package has been locked and the total has been calculated. Please do the payment to proceed',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Waiting for Payment Confirmation',
            'code' => 'PW',
            'description' => 'Your payment has been process. Please wait for an email from us to confirm the payment',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Registration Complete',
            'code' => 'AR',
            'description' => 'Your Registration has been completed. See you at the conference',
        ]);


        // Paper Submission Status
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
            'label' => 'Rejected',
            'code' => 'R',
            'description' => 'Sorry, your submission has been rejected',
        ]);

        DB::table('status')->insertTs([
            'label' => 'Accepted',
            'code' => 'A',
            'description' => 'Congratulation, your submission has been accepted to the conference',
        ]);
    }
}
