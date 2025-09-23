<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::updateOrCreate(
            [
                'email' => 'admin@admin.com',
            ],
            [
                'name' => 'Admin Principal',
                'phone' => '0000000000',
                'code' => 'admin001',
                'confirm' => 1,
                'password' => Hash::make('password'),
                'etat' => 1,
                'sys_types_user_id' => 1,
            ]
        );
    }
}

