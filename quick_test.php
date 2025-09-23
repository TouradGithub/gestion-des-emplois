<?php
/**
 * اختبار سريع للدوال المحدثة
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\EmploiTempsController;
use Illuminate\Http\Request;

echo "=== اختبار الدوال المحدثة ===\n\n";

$controller = new EmploiTempsController();

// اختبار جلب الأساتذة
echo "1. اختبار جلب الأساتذة (class_id=1, trimester_id=1):\n";
$request = new Request([
    'class_id' => 1,
    'trimester_id' => 1
]);

$response = $controller->getTeachers($request);
$teachersData = json_decode($response->getContent(), true);

echo "الاستجابة: " . $response->getContent() . "\n\n";

if (isset($teachersData['data']) && count($teachersData['data']) > 0) {
    echo "تم العثور على " . count($teachersData['data']) . " أستاذ\n";
    foreach ($teachersData['data'] as $teacher) {
        echo "- {$teacher['full_name']} (ID: {$teacher['id']})\n";
        if (isset($teacher['subjects'])) {
            echo "  عدد المواد: " . count($teacher['subjects']) . "\n";
        }
    }

    // اختبار جلب المواد للأستاذ الأول
    echo "\n2. اختبار جلب المواد للأستاذ الأول:\n";
    $firstTeacher = $teachersData['data'][0];

    $request2 = new Request([
        'teacher_id' => $firstTeacher['id'],
        'class_id' => 1,
        'trimester_id' => 1
    ]);

    $response2 = $controller->getSubjects($request2);
    $subjectsData = json_decode($response2->getContent(), true);

    echo "الاستجابة: " . $response2->getContent() . "\n";

    if (isset($subjectsData['subjects'])) {
        echo "عدد المواد: " . count($subjectsData['subjects']) . "\n";
        foreach ($subjectsData['subjects'] as $subject) {
            echo "- {$subject['name']} (معامل: {$subject['coefficient']})\n";
        }
    }

} else {
    echo "لم يتم العثور على أساتذة أو حدث خطأ\n";
    if (isset($teachersData['error'])) {
        echo "خطأ: " . $teachersData['error'] . "\n";
    }
}

echo "\n=== انتهى الاختبار ===\n";
?>
