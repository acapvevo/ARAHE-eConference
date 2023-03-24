<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'admin1',
            'username' => 'admin1',
            'password' => Hash::make('admin1'),
            'email' => 'admin1@gmail.com'
        ]);

        Admin::create([
            'name' => 'Dr. Asimah',
            'username' => 'asimah',
            'password' => Hash::make('asimah'),
            'email' => 'zsyimah@ftv.upsi.edu.my'
        ]);

        Admin::create([
            'name' => 'Dr. Sarimah',
            'username' => 'sarimah',
            'password' => Hash::make('sarimah'),
            'email' => 'isarimah1353@gmail.com'
        ]);

        Admin::create([
            'name' => 'Dr. Suriani',
            'username' => 'suriani',
            'password' => Hash::make('suriani'),
            'email' => 'sueforlife.mohamed@gmail.com'
        ]);

        Admin::create([
            'name' => 'Dr Rahimah',
            'username' => 'rahimah',
            'password' => Hash::make('rahimah'),
            'email' => 'imah_upm@upm.edu.my'
        ]);
    }
}
