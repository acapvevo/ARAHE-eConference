<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_payment')->insertTs([
            'label' => 'Success',
            'code' => '1',
            'description' => 'Your payment has been successfully processed'
        ]);

        DB::table('status_payment')->insertTs([
            'label' => 'Pending',
            'code' => '2',
            'description' => 'Your payment still in pending, Please complete your payment by click the payment link'
        ]);

        DB::table('status_payment')->insertTs([
            'label' => 'Failed',
            'code' => '3',
            'description' => 'Your payment was failed, Please make new payment'
        ]);

        DB::table('status_payment')->insertTs([
            'label' => 'Cancelled',
            'code' => '4',
            'description' => 'Your payment has been cancelled, Please make new payment'
        ]);

        DB::table('status_payment')->insertTs([
            'label' => 'Waiting',
            'code' => '5',
            'description' => 'Your payment currently being processed, Please wait for a few minutes'
        ]);

        DB::table('status_payment')->insertTs([
            'label' => 'Expired',
            'code' => '6',
            'description' => 'Your payment session has expired, Please make new payment'
        ]);
    }
}
