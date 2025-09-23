<?php
/**
 * ملف اختبار لوظائف EmploiTempsController الجديدة
 * يمكن تشغيله عبر artisan tinker أو كسكريبت منفصل
 */

// تحميل Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Classe;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Anneescolaire;
use App\Models\SubjectTeacher;

echo "=== اختبار وظائف جلب الأساتذة والمواد ===\n\n";

// اختبار جلب الأساتذة بناءً على الفصل والسمستر
echo "1. اختبار جلب الأساتذة للفصل الأول:\n";
$classe = Classe::first();
$anneeActive = Anneescolaire::where('is_active', true)->first();

if ($classe && $anneeActive) {
    echo "الفصل: {$classe->nom}\n";
    echo "السنة الدراسية: {$anneeActive->year}\n";
    echo "التخصص ID: {$classe->specialite_id}\n";

    // جلب الأساتذة
    $teachers = Teacher::whereHas('subjects', function ($query) use ($classe, $anneeActive) {
        $query->where('specialite_id', $classe->specialite_id)
              ->whereHas('teachers', function ($subQuery) use ($anneeActive) {
                  $subQuery->where('annee_id', $anneeActive->id);
              });
    })
    ->with(['subjects' => function ($query) use ($classe, $anneeActive) {
        $query->where('specialite_id', $classe->specialite_id)
              ->whereHas('teachers', function ($subQuery) use ($anneeActive) {
                  $subQuery->where('annee_id', $anneeActive->id);
              });
    }])
    ->get();

    echo "عدد الأساتذة المتاحين: " . $teachers->count() . "\n";

    foreach ($teachers as $teacher) {
        echo "- {$teacher->nom} {$teacher->prenom}\n";
        foreach ($teacher->subjects as $subject) {
            echo "  * {$subject->name} (معامل: {$subject->coefficient})\n";
        }
    }
} else {
    echo "لا توجد فصول أو سنة دراسية نشطة\n";
}

echo "\n2. اختبار جلب المواد لأستاذ معين:\n";
$teacher = Teacher::first();
if ($teacher) {
    echo "الأستاذ: {$teacher->nom} {$teacher->prenom}\n";

    $subjects = Subject::whereHas('teachers', function ($q) use ($teacher, $anneeActive) {
        $q->where('teacher_id', $teacher->id);
        if ($anneeActive) {
            $q->where('annee_id', $anneeActive->id);
        }
    })->get();

    echo "عدد المواد التي يدرسها: " . $subjects->count() . "\n";

    foreach ($subjects as $subject) {
        echo "- {$subject->name} (معامل: {$subject->coefficient})\n";
    }
} else {
    echo "لا يوجد أساتذة في قاعدة البيانات\n";
}

echo "\n3. إحصائيات عامة:\n";
echo "إجمالي الفصول: " . Classe::count() . "\n";
echo "إجمالي الأساتذة: " . Teacher::count() . "\n";
echo "إجمالي المواد: " . Subject::count() . "\n";
echo "إجمالي تعيينات الأساتذة للمواد: " . SubjectTeacher::count() . "\n";

echo "\n=== انتهى الاختبار ===\n";
