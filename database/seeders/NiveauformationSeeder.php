<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiveauformationSeeder extends Seeder
{
    public function run()
    {
        $niveaux = [
            ['nom' => 'Licence', 'ordre' => 1],
            ['nom' => 'Master', 'ordre' => 2],
            ['nom' => 'Doctorat', 'ordre' => 3],
        ];
        foreach ($niveaux as $niveau) {
            DB::table('niveauformations')->updateOrInsert([
                'nom' => $niveau['nom'],
            ], $niveau);
        }
    }
}
