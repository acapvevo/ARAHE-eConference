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
            'username' => 'admin2',
            'password' => Hash::make('admin2'),
            'email' => 'zsyimah@ftv.upsi.edu.my'
        ]);

        Admin::create([
            'name' => 'Dr. Sarimah',
            'username' => 'admin3',
            'password' => Hash::make('admin3'),
            'email' => 'isarimah1353@gmail.com'
        ]);

        Admin::create([
            'name' => 'Dr. Suriani',
            'username' => 'admin4',
            'password' => Hash::make('admin4'),
            'email' => 'sueforlife.mohamed@gmail.com'
        ]);
    }
}
