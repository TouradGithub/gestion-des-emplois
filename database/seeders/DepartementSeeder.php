<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartementSeeder extends Seeder
{
    public function run()
    {
        $departements = [
            ['name' => 'Informatique', 'code' => 'INFO'],
            ['name' => 'Mathématiques', 'code' => 'MATH'],
            ['name' => 'Physique', 'code' => 'PHYS'],
            ['name' => 'Chimie', 'code' => 'CHIM'],
        ];
        foreach ($departements as $dep) {
            DB::table('departements')->updateOrInsert([
                'name' => $dep['name'],
            ], $dep);
        }
    }
}
