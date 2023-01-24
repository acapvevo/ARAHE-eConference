<?php

namespace Database\Seeders;

use Faker\Generator;
use App\Models\Address;
use App\Models\Contact;
use App\Models\Institution;
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

        $participant = Participant::create([
            'name' => 'reviewer1',
            'registration_id' => 'ARAHE-2023-1',
            'email' => 'reviewer1@gmail.com',
            'password' => Hash::make('reviewer1'),
        ]);

        $participant->contact()->save(new Contact([
            'phoneNumber' => $faker->mobileNumber(),
        ]));

        $participant->institution()->save(new Institution([
            'name' => 'Univeriti Teknologi Malaysia',
            'faculty' => 'Computing',
            'department' => 'Software Engineering',
        ]));

        $participant->address()->save(new Address([
            'lineOne' => $faker->buildingNumber(),
            'lineTwo' => $faker->streetName(),
            'lineThree' => $faker->township(),
            'city' => $faker->city(),
            'postcode' => $faker->postcode(),
            'state' => $faker->state(),
            'country' => $faker->country(),
        ]));

        Participant::create([
            'name' => 'participant1',
            'registration_id' => 'ARAHE-2023-2',
            'email' => 'participant1@gmail.com',
            'password' => Hash::make('participant1'),
        ]);

        $participant->contact()->save(new Contact([
            'phoneNumber' => $faker->mobileNumber(),
            'faxNumber' => $faker->fixedLineNumber(),
        ]));

        $participant->institution()->save(new Institution([
            'name' => 'Univeriti Teknologi Petronas',
            'faculty' => 'Engineering',
            'department' => 'Chemical Engineering',
        ]));

        $participant->address()->save(new Address([
            'lineOne' => $faker->buildingNumber(),
            'lineTwo' => $faker->streetName(),
            'city' => $faker->city(),
            'postcode' => $faker->postcode(),
            'state' => $faker->state(),
            'country' => $faker->country(),
        ]));
    }
}
