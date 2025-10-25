<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up()
    {
        // إضافة بيانات تجريبية للحضور
        $emplois = DB::table('emplois_temps')
            ->join('teachers', 'emplois_temps.teacher_id', '=', 'teachers.id')
            ->select('emplois_temps.id', 'emplois_temps.teacher_id', 'teachers.name')
            ->limit(5)
            ->get();

        $dates = [
            Carbon::today()->subDays(4)->format('Y-m-d'),
            Carbon::today()->subDays(3)->format('Y-m-d'),
            Carbon::today()->subDays(2)->format('Y-m-d'),
            Carbon::today()->subDays(1)->format('Y-m-d'),
            Carbon::today()->format('Y-m-d'),
        ];

        foreach ($dates as $date) {
            foreach ($emplois as $emploi) {
                DB::table('pointages')->insert([
                    'emploi_temps_id' => $emploi->id,
                    'teacher_id' => $emploi->teacher_id,
                    'date_pointage' => $date,
                    'statut' => (rand(1, 10) <= 8) ? 'present' : 'absent',
                    'remarques' => 'Test pointage - ' . $emploi->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        DB::table('pointages')->truncate();
    }
};
