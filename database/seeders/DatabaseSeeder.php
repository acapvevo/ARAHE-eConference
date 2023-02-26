<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SuperAdminSeeder::class,
            AdminSeeder::class,
            ParticipantSeeder::class,
            ReviewerSeeder::class,
            ManualSeeder::class,
            StatusSeeder::class,
            ScaleSeeder::class,
            StatusPaymentSeeder::class,
            LocalitySeeder::class,
            ParticipantTitleSeeder::class,
            ParticipantTypeSeeder::class,
        ]);
    }
}
