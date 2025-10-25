<?php

// إنشاء بيانات تجريبية للحضور
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// بدء التطبيق
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== إنشاء بيانات تجريبية للحضور ===\n\n";

try {
    // الحصول على الجداول الدراسية والأساتذة
    $emplois = DB::table('emplois_temps')
        ->join('teachers', 'emplois_temps.teacher_id', '=', 'teachers.id')
        ->select('emplois_temps.id', 'emplois_temps.teacher_id', 'teachers.name as teacher_name')
        ->limit(10)
        ->get();

    if ($emplois->isEmpty()) {
        echo "❌ لا توجد جداول دراسية في قاعدة البيانات\n";
        exit;
    }

    echo "📚 تم العثور على " . $emplois->count() . " جدول دراسي\n\n";

    $pointages_created = 0;

    // إنشاء بيانات حضور لآخر 5 أيام
    for ($i = 4; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i)->format('Y-m-d');

        echo "📅 إنشاء حضور لتاريخ: $date\n";

        foreach ($emplois as $emploi) {
            // تحديد الحالة عشوائياً (80% حضور، 20% غياب)
            $statut = (rand(1, 10) <= 8) ? 'present' : 'absent';

            // التحقق من عدم وجود سجل مسبق
            $existing = DB::table('pointages')
                ->where('emploi_temps_id', $emploi->id)
                ->where('date_pointage', $date)
                ->exists();

            if (!$existing) {
                // إنشاء سجل الحضور
                DB::table('pointages')->insert([
                    'emploi_temps_id' => $emploi->id,
                    'teacher_id' => $emploi->teacher_id,
                    'date_pointage' => $date,
                    'statut' => $statut,
                    'remarques' => $statut === 'present'
                        ? "حضور عادي - " . $emploi->teacher_name
                        : "غياب - " . $emploi->teacher_name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $pointages_created++;
                echo "  ✅ {$emploi->teacher_name}: $statut\n";
            } else {
                echo "  ⚠️  {$emploi->teacher_name}: موجود مسبقاً\n";
            }
        }
        echo "\n";
    }

    echo "=== النتائج النهائية ===\n";
    echo "✅ تم إنشاء $pointages_created سجل حضور جديد\n";

    // إحصائيات سريعة
    $total_pointages = DB::table('pointages')->count();
    $total_present = DB::table('pointages')->where('statut', 'present')->count();
    $total_absent = DB::table('pointages')->where('statut', 'absent')->count();

    echo "📊 إجمالي سجلات الحضور: $total_pointages\n";
    echo "   - حاضر: $total_present\n";
    echo "   - غائب: $total_absent\n";

    // إحصائيات اليوم
    $today = Carbon::today()->format('Y-m-d');
    $today_present = DB::table('pointages')
        ->where('statut', 'present')
        ->where('date_pointage', $today)
        ->count();
    $today_absent = DB::table('pointages')
        ->where('statut', 'absent')
        ->where('date_pointage', $today)
        ->count();

    echo "\n📅 حضور اليوم ($today):\n";
    echo "   - حاضر: $today_present\n";
    echo "   - غائب: $today_absent\n";

    echo "\n🎉 تم إنشاء البيانات التجريبية بنجاح!\n";
    echo "\nيمكنك الآن اختبار النظام عبر:\n";
    echo "- /admin/pointages (قائمة الحضور)\n";
    echo "- /admin/pointages/create (إضافة حضور جديد)\n";
    echo "- /admin/pointages/rapide/aujourd-hui (الحضور السريع)\n";

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
