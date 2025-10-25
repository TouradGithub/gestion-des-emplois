<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sys_types_users')->insert([
            [
                'id' => 1,
                'libelle' => 'Admin',
                'libelle_ar' => 'مدير النظام',
                'ordre' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'libelle' => 'Teacher',
                'libelle_ar' => 'أستاذ',
                'ordre' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
