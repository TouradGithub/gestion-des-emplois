<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إضافة دور الطالب إلى جدول الأدوار
        DB::table('roles')->insertOrIgnore([
            [
                'id' => 3,
                'name' => 'Student',
                'display_name' => 'طالب',
                'description' => 'دور الطالب في النظام',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
