<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::updateOrCreate([
            'email' => 'MEMBER@fitness.com',
             'first_name' => 'John',
                'last_name'  => 'Fitness',
                'password'   => Hash::make('password'),
                'phone'      => '+0000000000',
                'address'    => 'Backoffice',
        ]);
                    
    }
}
