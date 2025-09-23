<?php
/**
 * اختبار النظام المحدث مع class_id
 */

// تحميل Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SubjectTeacher;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classe;
use App\Models\Trimester;
use App\Models\Anneescolaire;

echo "=== اختبار النظام المحدث مع class_id ===\n\n";

// 1. اختبار جلب البيانات المحدثة
echo "1. عرض البيانات المحدثة في subject_teacher:\n";
$subjectTeachers = SubjectTeacher::with(['subject', 'teacher', 'trimester', 'classe'])->get();

foreach ($subjectTeachers as $st) {
    echo "- الأستاذ: " . ($st->teacher ? $st->teacher->name : 'N/A') . "\n";
    echo "  المادة: " . ($st->subject ? $st->subject->name : 'N/A') . "\n";
    echo "  الفصل الدراسي: " . ($st->trimester ? $st->trimester->name : 'N/A') . "\n";
    echo "  الفصل: " . ($st->classe ? $st->classe->nom : 'N/A') . "\n";
    echo "  ---\n";
}

// 2. إنشاء subject_teacher جديد لاختبار النظام
echo "\n2. إنشاء تعيين جديد لاختبار النظام:\n";

$teacher = Teacher::first();
$subject = Subject::first();
$trimester = Trimester::first();
$classe = Classe::first();
$annee = Anneescolaire::where('is_active', true)->first();

if ($teacher && $subject && $trimester && $classe && $annee) {
    try {
        // التحقق من عدم وجود تكرار
        $existing = SubjectTeacher::where([
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'trimester_id' => $trimester->id,
            'class_id' => $classe->id,
            'annee_id' => $annee->id
        ])->first();

        if (!$existing) {
            $newST = SubjectTeacher::create([
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
                'trimester_id' => $trimester->id,
                'class_id' => $classe->id,
                'annee_id' => $annee->id
            ]);

            echo "✅ تم إنشاء تعيين جديد بنجاح!\n";
            echo "   ID: {$newST->id}\n";
            echo "   الأستاذ: {$teacher->name}\n";
            echo "   المادة: {$subject->name}\n";
            echo "   الفصل: {$classe->nom}\n";
        } else {
            echo "⚠️ التعيين موجود مسبقاً\n";
        }
    } catch (Exception $e) {
        echo "❌ خطأ في إنشاء التعيين: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ بيانات ناقصة لإنشاء التعيين\n";
}

// 3. اختبار جلب الأساتذة للفصل المحدد
echo "\n3. اختبار جلب الأساتذة للفصل المحدد:\n";
if ($classe && $trimester && $annee) {
    $teachers = Teacher::whereHas('subjects', function ($query) use ($classe, $trimester, $annee) {
        $query->where('specialite_id', $classe->specialite_id)
              ->whereHas('teachers', function ($subQuery) use ($trimester, $annee, $classe) {
                  $subQuery->where('trimester_id', $trimester->id)
                           ->where('annee_id', $annee->id)
                           ->where('class_id', $classe->id);
              });
    })
    ->with(['subjects' => function ($query) use ($classe, $trimester, $annee) {
        $query->where('specialite_id', $classe->specialite_id)
              ->whereHas('teachers', function ($subQuery) use ($trimester, $annee, $classe) {
                  $subQuery->where('trimester_id', $trimester->id)
                           ->where('annee_id', $annee->id)
                           ->where('class_id', $classe->id);
              });
    }])
    ->get();

    echo "عدد الأساتذة المتاحين للفصل '{$classe->nom}': " . $teachers->count() . "\n";

    foreach ($teachers as $teacher) {
        echo "- {$teacher->name}\n";
        foreach ($teacher->subjects as $subject) {
            echo "  * {$subject->name}\n";
        }
    }
} else {
    echo "❌ بيانات ناقصة لاختبار جلب الأساتذة\n";
}

echo "\n=== انتهى الاختبار ===\n";
