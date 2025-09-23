<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClasseSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            ['nom' => 'L1 Info A', 'niveau_pedagogique_id' => 1, 'specialite_id' => 1, 'annee_id' => 1],
            ['nom' => 'L2 Info B', 'niveau_pedagogique_id' => 1, 'specialite_id' => 2, 'annee_id' => 1],
            ['nom' => 'M1 Math', 'niveau_pedagogique_id' => 2, 'specialite_id' => 3, 'annee_id' => 2],
            ['nom' => 'M2 Math', 'niveau_pedagogique_id' => 2, 'specialite_id' => 3, 'annee_id' => 2],
        ];
        foreach ($classes as $classe) {
            DB::table('classes')->updateOrInsert([
                'nom' => $classe['nom'],
            ], $classe);
        }
    }
}
