<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\EmploiTempsController;
use Illuminate\Http\Request;

echo "=== اختبار الطلب المحدد ===\n\n";

$controller = new EmploiTempsController();

// اختبار نفس المعاملات التي ذكرها المستخدم
echo "اختبار: class_id=1, trimester_id=2\n";
$request = new Request([
    'class_id' => 1,
    'trimester_id' => 2
]);

$response = $controller->getTeachers($request);
$data = json_decode($response->getContent(), true);

echo "الاستجابة الكاملة:\n";
echo $response->getContent() . "\n\n";

echo "تحليل الاستجابة:\n";
if (isset($data['data'])) {
    echo "عدد العناصر في data: " . count($data['data']) . "\n";
} else {
    echo "لا يوجد مفتاح 'data' في الاستجابة\n";
}

if (isset($data['total'])) {
    echo "قيمة total: " . $data['total'] . "\n";
}

if (isset($data['rows'])) {
    echo "عدد العناصر في rows: " . count($data['rows']) . "\n";
}

if (isset($data['error'])) {
    echo "رسالة خطأ: " . $data['error'] . "\n";
}

// فحص البيانات في قاعدة البيانات لهذا المزيج
echo "\n=== فحص قاعدة البيانات ===\n";
$subjectTeachers = \App\Models\SubjectTeacher::where('class_id', 1)
    ->where('trimester_id', 2)
    ->with(['teacher', 'subject', 'trimester'])
    ->get();

echo "السجلات الموجودة في subject_teacher لـ class_id=1 و trimester_id=2:\n";
echo "عدد السجلات: " . $subjectTeachers->count() . "\n\n";

foreach ($subjectTeachers as $st) {
    echo "ID: {$st->id}\n";
    echo "teacher_id: {$st->teacher_id}\n";
    echo "subject_id: {$st->subject_id}\n";
    echo "class_id: {$st->class_id}\n";
    echo "trimester_id: {$st->trimester_id}\n";
    echo "annee_id: {$st->annee_id}\n";
    echo "الأستاذ: " . ($st->teacher ? $st->teacher->name : 'لا يوجد') . "\n";
    echo "المادة: " . ($st->subject ? $st->subject->name : 'لا يوجد') . "\n";
    echo "الفصل الدراسي: " . ($st->trimester ? $st->trimester->name : 'لا يوجد') . "\n";
    echo "---\n";
}

// فحص السنة النشطة
echo "\nالسنة الدراسية النشطة:\n";
$activeYear = \App\Models\Anneescolaire::where('is_active', true)->first();
if ($activeYear) {
    echo "ID: {$activeYear->id}\n";
    echo "السنة: {$activeYear->year}\n";
} else {
    echo "لا توجد سنة دراسية نشطة!\n";
}

echo "\n=== انتهى الفحص ===\n";
?>
