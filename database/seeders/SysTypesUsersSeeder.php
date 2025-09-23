<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SysTypesUsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('sys_types_users')->insert([
            ['libelle' => 'Administrateur', 'libelle_ar' => 'مدير', 'ordre' => 1],
            ['libelle' => 'Professeur', 'libelle_ar' => 'أستاذ', 'ordre' => 2],
            ['libelle' => 'Étudiant', 'libelle_ar' => 'طالب', 'ordre' => 3],
        ]);
    }
}

