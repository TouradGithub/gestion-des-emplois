<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\SysTypesUsersSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SysTypesUsersSeeder::class,
            UsersSeeder::class,
            AnneescolaireSeeder::class,
            ClasseSeeder::class,
            DepartementSeeder::class,
            NiveauformationSeeder::class,
            NiveauPedagogiqueSeeder::class,
            SalleDeClasseSeeder::class,
            SpecialitySeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            SubjectTeacherSeeder::class,
        ]);
    }
}
