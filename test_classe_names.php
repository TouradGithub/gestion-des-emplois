<?php
/**
 * اختبار عرض أسماء الفصول في subjects_teachers
 */

// تحميل Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SubjectTeacher;

echo "=== اختبار عرض أسماء الفصول ===\n\n";

// جلب البيانات مع العلاقات
$subjectTeachers = SubjectTeacher::with(['subject', 'teacher', 'trimester', 'classe'])->get();

echo "عدد السجلات: " . $subjectTeachers->count() . "\n\n";

foreach ($subjectTeachers as $st) {
    echo "=== سجل رقم {$st->id} ===\n";
    echo "الأستاذ: " . ($st->teacher ? $st->teacher->name : 'N/A') . "\n";
    echo "المادة: " . ($st->subject ? $st->subject->name : 'N/A') . "\n";
    echo "الفصل الدراسي: " . ($st->trimester ? $st->trimester->name : 'N/A') . "\n";
    echo "الفصل (classe): " . ($st->classe ? $st->classe->nom : 'N/A') . "\n";
    echo "class_id: " . $st->class_id . "\n";

    // التحقق من العلاقة
    if ($st->classe) {
        echo "✅ العلاقة مع الفصل تعمل بشكل صحيح\n";
    } else {
        echo "❌ العلاقة مع الفصل لا تعمل أو class_id فارغ\n";

        // محاولة جلب الفصل مباشرة
        if ($st->class_id) {
            $classe = \App\Models\Classe::find($st->class_id);
            if ($classe) {
                echo "   🔍 الفصل موجود في قاعدة البيانات: {$classe->nom}\n";
            } else {
                echo "   ❌ الفصل غير موجود في قاعدة البيانات\n";
            }
        }
    }
    echo "---\n\n";
}

echo "=== انتهى الاختبار ===\n";
