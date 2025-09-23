<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnneescolaireSeeder extends Seeder
{
    public function run()
    {
        for ($i = 2020; $i <= 2025; $i++) {
            DB::table('anneescolaires')->insert([
                'annee' => $i . '-' . ($i+1),
                'date_debut' => $i . '-09-01',
                'date_fin' => ($i+1) . '-06-30',
                'is_active' => ($i == 2025) ? 1 : 0,
            ]);
        }
    }
}
