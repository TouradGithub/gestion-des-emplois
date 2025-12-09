<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pointage;
use App\Models\EmploiTemps;
use App\Models\Anneescolaire;
use Carbon\Carbon;

class MarkPastAbsences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pointages:mark-past-absences
                            {--from= : تاريخ البداية (YYYY-MM-DD)}
                            {--to= : تاريخ النهاية (YYYY-MM-DD)}
                            {--dry-run : معاينة فقط بدون حفظ}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تسجيل الغياب التلقائي لجميع الحصص الماضية غير المسجلة (الأستاذ غائب حتى يثبت العكس)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $anneeActive = Anneescolaire::where('is_active', true)->first();

        if (!$anneeActive) {
            $this->error('لا توجد سنة دراسية نشطة!');
            return 1;
        }

        // تحديد نطاق التواريخ
        $startOfCurrentWeek = Carbon::now()->startOfWeek();

        // تاريخ النهاية: نهاية الأسبوع الماضي (قبل الأسبوع الحالي)
        $endDate = $this->option('to')
            ? Carbon::parse($this->option('to'))
            : $startOfCurrentWeek->copy()->subDay(); // يوم الأحد الماضي

        // تاريخ البداية: بداية السنة الدراسية أو التاريخ المحدد
        $startDate = $this->option('from')
            ? Carbon::parse($this->option('from'))
            : Carbon::parse($anneeActive->start_date ?? $anneeActive->created_at);

        $isDryRun = $this->option('dry-run');

        $this->info('===========================================');
        $this->info('  تسجيل الغياب التلقائي للحصص الماضية');
        $this->info('===========================================');
        $this->info("السنة الدراسية: {$anneeActive->name}");
        $this->info("من: {$startDate->format('Y-m-d')}");
        $this->info("إلى: {$endDate->format('Y-m-d')}");

        if ($isDryRun) {
            $this->warn('>>> وضع المعاينة - لن يتم حفظ أي بيانات <<<');
        }

        $this->newLine();

        // الحصول على جميع جداول الدراسة للسنة النشطة
        $emplois = EmploiTemps::where('annee_id', $anneeActive->id)
            ->with(['jour', 'teacher', 'classe', 'subject'])
            ->get();

        if ($emplois->isEmpty()) {
            $this->warn('لا توجد حصص مجدولة للسنة الدراسية الحالية.');
            return 0;
        }

        $this->info("عدد الحصص المجدولة: {$emplois->count()}");
        $this->newLine();

        $totalCreated = 0;
        $totalSkipped = 0;

        // المرور على كل يوم في النطاق
        $currentDate = $startDate->copy();

        $progressBar = $this->output->createProgressBar($startDate->diffInDays($endDate) + 1);
        $progressBar->start();

        while ($currentDate <= $endDate) {
            // تجاوز عطلة نهاية الأسبوع إذا كانت الأيام غير مستخدمة
            $dayOfWeek = $currentDate->dayOfWeek;
            $dayOrder = $dayOfWeek === 0 ? 7 : $dayOfWeek; // تحويل الأحد من 0 إلى 7

            // البحث عن الحصص المجدولة لهذا اليوم
            foreach ($emplois as $emploi) {
                if (!$emploi->jour || $emploi->jour->ordre != $dayOrder) {
                    continue;
                }

                // التحقق من عدم وجود تسجيل حضور لهذه الحصة في هذا التاريخ
                $existingPointage = Pointage::where('emploi_temps_id', $emploi->id)
                    ->where('date_pointage', $currentDate->format('Y-m-d'))
                    ->first();

                if ($existingPointage) {
                    $totalSkipped++;
                    continue;
                }

                // إنشاء تسجيل غياب
                if (!$isDryRun) {
                    Pointage::create([
                        'emploi_temps_id' => $emploi->id,
                        'teacher_id' => $emploi->teacher_id,
                        'date_pointage' => $currentDate->format('Y-m-d'),
                        'statut' => 'absent',
                        'remarques' => 'غياب تلقائي - لم يتم تسجيل الحضور',
                        'created_by' => 1, // النظام
                    ]);
                }

                $totalCreated++;
            }

            $currentDate->addDay();
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('===========================================');
        $this->info('  النتائج');
        $this->info('===========================================');

        if ($isDryRun) {
            $this->warn("سيتم إنشاء: {$totalCreated} تسجيل غياب");
        } else {
            $this->info("تم إنشاء: {$totalCreated} تسجيل غياب");
        }

        $this->info("تم تجاوز: {$totalSkipped} (مسجلة مسبقاً)");

        return 0;
    }
}
