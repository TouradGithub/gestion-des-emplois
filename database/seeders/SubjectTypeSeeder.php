<?php

namespace Database\Seeders;

use App\Models\SubjectType;
use Illuminate\Database\Seeder;

class SubjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'TD',
                'code' => 'td',
                'color' => '#28a745',
                'icon' => 'fas fa-chalkboard',
                'order' => 1,
            ],
            [
                'name' => 'TP',
                'code' => 'tp',
                'color' => '#007bff',
                'icon' => 'fas fa-flask',
                'order' => 2,
            ],
            [
                'name' => 'Project',
                'code' => 'project',
                'color' => '#fd7e14',
                'icon' => 'fas fa-project-diagram',
                'order' => 3,
            ],
        ];

        foreach ($types as $type) {
            SubjectType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
