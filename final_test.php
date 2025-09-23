<?php
/**
 * اختبار شامل للنظام
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\EmploiTempsController;
use Illuminate\Http\Request;

echo "=== اختبار شامل للنظام المحدث ===\n\n";

$controller = new EmploiTempsController();

// اختبار 1: بيانات صحيحة
echo "1. اختبار بيانات صحيحة (class_id=1, trimester_id=1):\n";
$request = new Request(['class_id' => 1, 'trimester_id' => 1]);
$response = $controller->getTeachers($request);
$data = json_decode($response->getContent(), true);
echo "النتيجة: " . ($data['data'] && count($data['data']) > 0 ? "نجح ✓" : "فشل ✗") . "\n";
echo "عدد الأساتذة: " . count($data['data']) . "\n\n";

// اختبار 2: class_id مفقود
echo "2. اختبار class_id مفقود:\n";
$request = new Request(['trimester_id' => 1]);
$response = $controller->getTeachers($request);
$data = json_decode($response->getContent(), true);
echo "النتيجة: " . (isset($data['data']) && count($data['data']) == 0 ? "نجح ✓" : "فشل ✗") . "\n\n";

// اختبار 3: trimester_id مفقود
echo "3. اختبار trimester_id مفقود:\n";
$request = new Request(['class_id' => 1]);
$response = $controller->getTeachers($request);
$data = json_decode($response->getContent(), true);
echo "النتيجة: " . (isset($data['data']) && count($data['data']) == 0 ? "نجح ✓" : "فشل ✗") . "\n\n";

// اختبار 4: class_id غير موجود
echo "4. اختبار class_id غير موجود (999):\n";
$request = new Request(['class_id' => 999, 'trimester_id' => 1]);
$response = $controller->getTeachers($request);
$data = json_decode($response->getContent(), true);
echo "النتيجة: " . (isset($data['error']) || (isset($data['data']) && count($data['data']) == 0) ? "نجح ✓" : "فشل ✗") . "\n\n";

// اختبار 5: جلب المواد
echo "5. اختبار جلب المواد للأستاذ رقم 1:\n";
$request = new Request(['teacher_id' => 1, 'class_id' => 1, 'trimester_id' => 1]);
$response = $controller->getSubjects($request);
$data = json_decode($response->getContent(), true);
echo "النتيجة: " . (isset($data['subjects']) && count($data['subjects']) > 0 ? "نجح ✓" : "فشل ✗") . "\n";
echo "عدد المواد: " . (isset($data['subjects']) ? count($data['subjects']) : 0) . "\n\n";

echo "=== انتهى الاختبار الشامل ===\n";
?>
