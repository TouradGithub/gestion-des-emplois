<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        $teachers = [
            ['name' => 'أحمد علي', 'nni' => '10000001', 'email' => 'ahmed.ali@school.com', 'phone' => '0600000001', 'gender' => '1'],
            ['name' => 'سعاد محمد', 'nni' => '10000002', 'email' => 'souad.mohamed@school.com', 'phone' => '0600000002', 'gender' => '0'],
            ['name' => 'يوسف عمر', 'nni' => '10000003', 'email' => 'youssef.omer@school.com', 'phone' => '0600000003', 'gender' => '1'],
        ];
        foreach ($teachers as $teacher) {
            DB::table('teachers')->updateOrInsert([
                'email' => $teacher['email'],
            ], $teacher);
        }
    }
}
