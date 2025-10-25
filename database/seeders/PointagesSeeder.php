<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pointage;
use App\Models\EmploiTemps;
use App\Models\Teacher;
use Carbon\Carbon;

class PointagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على الأساتذة والجداول الدراسية
        $teachers = Teacher::all();
        $emplois = EmploiTemps::all();
        
        if ($teachers->isEmpty() || $emplois->isEmpty()) {
            $this->command->warn('لا توجد أساتذة أو جداول دراسية في قاعدة البيانات');
            return;
        }

        // إنشاء بيانات حضور للأسبوع الماضي والحالي
        $dates = [];
        $startDate = Carbon::now()->subWeeks(1)->startOfWeek();
        
        // إنشاء 14 يوم من البيانات (أسبوعين)
        for ($i = 0; $i < 14; $i++) {
            $date = $startDate->copy()->addDays($i);
            // تجنب عطل نهاية الأسبوع
            if (!$date->isWeekend()) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        $this->command->info('إنشاء بيانات الحضور للتواريخ التالية:');
        foreach ($dates as $date) {
            $this->command->line("- $date");
        }

        $pointagesCreated = 0;

        foreach ($dates as $date) {
            foreach ($emplois as $emploi) {
                // احتمالية 80% للحضور، 15% للغياب، 5% عدم تسجيل
                $rand = rand(1, 100);
                
                if ($rand <= 80) {
                    $statut = 'present';
                } elseif ($rand <= 95) {
                    $statut = 'absent';
                } else {
                    continue; // لا يتم تسجيل حضور
                }

                // التحقق من عدم وجود تسجيل مسبق
                $existingPointage = Pointage::where('emploi_temps_id', $emploi->id)
                    ->where('date_pointage', $date)
                    ->first();

                if (!$existingPointage) {
                    $remarques = null;
                    
                    // إضافة ملاحظات عشوائية للغياب
                    if ($statut === 'absent') {
                        $remarques_options = [
                            'غياب بعذر طبي',
                            'مؤتمر علمي',
                            'مهمة إدارية',
                            'إجازة اضطرارية',
                            'غياب بدون عذر'
                        ];
                        $remarques = $remarques_options[array_rand($remarques_options)];
                    } elseif (rand(1, 100) <= 20) {
                        // 20% احتمالية لملاحظات على الحضور
                        $remarques_presence = [
                            'حضر في الوقت المحدد',
                            'تأخر 10 دقائق',
                            'حضور متميز',
                            'حضر مبكراً',
                            'انتهى مبكراً بإذن'
                        ];
                        $remarques = $remarques_presence[array_rand($remarques_presence)];
                    }

                    Pointage::create([
                        'emploi_temps_id' => $emploi->id,
                        'teacher_id' => $emploi->teacher_id,
                        'date_pointage' => $date,
                        'statut' => $statut,
                        'heure_arrivee' => $statut === 'present' ? 
                            Carbon::createFromFormat('Y-m-d', $date)->addHours(8)->addMinutes(rand(0, 30))->format('H:i') : null,
                        'heure_depart' => $statut === 'present' ? 
                            Carbon::createFromFormat('Y-m-d', $date)->addHours(12)->addMinutes(rand(0, 30))->format('H:i') : null,
                        'remarques' => $remarques,
                        'created_by' => 1, // افتراض أن المدير له ID = 1
                    ]);

                    $pointagesCreated++;
                }
            }
        }

        $this->command->info("تم إنشاء $pointagesCreated تسجيل حضور بنجاح!");

        // إحصائيات سريعة
        $totalPresent = Pointage::where('statut', 'present')->count();
        $totalAbsent = Pointage::where('statut', 'absent')->count();
        
        $this->command->table(
            ['الحالة', 'العدد'],
            [
                ['الحضور', $totalPresent],
                ['الغياب', $totalAbsent],
                ['الإجمالي', $totalPresent + $totalAbsent]
            ]
        );
    }
}
