<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalleDeClasseSeeder extends Seeder
{
    public function run()
    {
        $salles = [
            ['name' => 'Salle 101', 'code' => 'S101', 'capacity' => 30],
            ['name' => 'Salle 102', 'code' => 'S102', 'capacity' => 30],
            ['name' => 'Salle 201', 'code' => 'S201', 'capacity' => 40],
            ['name' => 'Salle 202', 'code' => 'S202', 'capacity' => 40],
        ];
        foreach ($salles as $salle) {
            DB::table('salle_de_classes')->updateOrInsert([
                'name' => $salle['name'],
            ], $salle);
        }
    }
}
