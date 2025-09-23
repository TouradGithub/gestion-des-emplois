<?php
/**
 * اختبار مباشر لوظيفة listSubjectTeacher
 */

// تحميل Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\SubjectTeacherController;
use Illuminate\Http\Request;

echo "=== اختبار وظيفة listSubjectTeacher مباشرة ===\n\n";

// إنشاء request مزيف
$request = new Request([
    'offset' => 0,
    'limit' => 3,
    'sort' => 'id',
    'order' => 'desc'
]);

// إنشاء controller وتشغيل الوظيفة
$controller = new SubjectTeacherController();
$response = $controller->listSubjectTeacher($request);

// تحويل النتيجة إلى array
$data = json_decode($response->getContent(), true);

echo "عدد السجلات الإجمالي: " . $data['total'] . "\n";
echo "عدد السجلات المعروضة: " . count($data['rows']) . "\n\n";

foreach ($data['rows'] as $index => $row) {
    echo "=== سجل " . ($index + 1) . " ===\n";
    echo "ID: " . $row['id'] . "\n";
    echo "المادة: " . $row['subject'] . "\n";
    echo "الأستاذ: " . $row['teacher'] . "\n";
    echo "الفصل الدراسي: " . $row['trimester'] . "\n";
    echo "الفصل: " . (isset($row['classe']) ? $row['classe'] : 'غير موجود') . "\n";
    echo "---\n\n";
}

echo "البيانات الكاملة JSON:\n";
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

echo "=== انتهى الاختبار ===\n";
