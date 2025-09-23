<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialitySeeder extends Seeder
{
    public function run()
    {
        $specialities = [
            ['name' => 'Développement Web', 'code' => 'DEVWEB', 'departement_id' => 1, 'niveau_formation_id' => 1],
            ['name' => 'Réseaux', 'code' => 'RESEAUX', 'departement_id' => 1, 'niveau_formation_id' => 2],
            ['name' => 'Analyse Mathématique', 'code' => 'ANAMATH', 'departement_id' => 2, 'niveau_formation_id' => 1],
        ];
        foreach ($specialities as $spec) {
            DB::table('specialities')->updateOrInsert([
                'name' => $spec['name'],
            ], $spec);
        }
    }
}
