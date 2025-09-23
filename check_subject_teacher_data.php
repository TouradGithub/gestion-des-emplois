<?php
/**
 * فحص البيانات في subject_teacher
 */

// تحميل Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SubjectTeacher;
use App\Models\Classe;
use App\Models\Anneescolaire;

echo "=== فحص البيانات في subject_teacher ===\n\n";

// عرض جميع السجلات
$subjectTeachers = SubjectTeacher::with(['subject', 'teacher', 'trimester', 'classe'])->get();

echo "إجمالي السجلات: " . $subjectTeachers->count() . "\n\n";

foreach ($subjectTeachers as $st) {
    echo "ID: {$st->id}\n";
    echo "الأستاذ: " . ($st->teacher ? $st->teacher->name : 'N/A') . "\n";
    echo "المادة: " . ($st->subject ? $st->subject->name : 'N/A') . "\n";
    echo "التخصص: " . ($st->subject && $st->subject->specialite ? $st->subject->specialite->name : 'N/A') . "\n";
    echo "الفصل الدراسي: " . ($st->trimester ? $st->trimester->name : 'N/A') . "\n";
    echo "الفصل: " . ($st->classe ? $st->classe->nom : 'N/A') . "\n";
    echo "class_id: {$st->class_id}\n";
    echo "trimester_id: {$st->trimester_id}\n";
    echo "annee_id: {$st->annee_id}\n";
    echo "---\n\n";
}

// فحص الفصول المتاحة
echo "=== الفصول المتاحة ===\n";
$classes = Classe::with('specialite')->get();
foreach ($classes as $classe) {
    echo "ID: {$classe->id} - {$classe->nom}";
    if ($classe->specialite) {
        echo " (تخصص: {$classe->specialite->name})";
    }
    echo "\n";
}

// فحص السنة الدراسية النشطة
echo "\n=== السنة الدراسية النشطة ===\n";
$activeYear = Anneescolaire::where('is_active', true)->first();
if ($activeYear) {
    echo "ID: {$activeYear->id} - {$activeYear->year}\n";
} else {
    echo "لا توجد سنة دراسية نشطة\n";
}

echo "\n=== انتهى الفحص ===\n";
