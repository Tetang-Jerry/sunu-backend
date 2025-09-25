<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@fitness.com'],
            [
                'first_name' => 'Admin',
                'last_name'  => 'Fitness',
                'password'   => Hash::make('password'),
                'phone'      => '+0000000000',
                'address'    => 'Backoffice',
                'date_of_birth' => null,
                'role'       => 'admin',
            ]
        );
    }
}
