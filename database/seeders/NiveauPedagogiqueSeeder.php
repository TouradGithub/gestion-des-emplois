<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NiveauPedagogiqueSeeder extends Seeder
{
    public function run()
    {
        $niveaux = [
            ['nom' => 'Cycle 1', 'ordre' => 1],
            ['nom' => 'Cycle 2', 'ordre' => 2],
        ];
        foreach ($niveaux as $niveau) {
            DB::table('niveau_pedagogiques')->updateOrInsert([
                'nom' => $niveau['nom'],
            ], $niveau);
        }
    }
}
