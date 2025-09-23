<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            ['name' => 'رياضيات', 'code' => 'MATH'],
            ['name' => 'فيزياء', 'code' => 'PHYS'],
            ['name' => 'كيمياء', 'code' => 'CHEM'],
            ['name' => 'معلوماتية', 'code' => 'INFO'],
        ];
        foreach ($subjects as $subject) {
            DB::table('subjects')->updateOrInsert([
                'code' => $subject['code'],
            ], $subject);
        }
    }
}
