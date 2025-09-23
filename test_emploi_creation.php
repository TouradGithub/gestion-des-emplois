<?php
/**
 * اختبار النظام المحدث لجلب الأساتذة والمواد في إنشاء الحصص
 */

// تحميل Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\EmploiTempsController;
use Illuminate\Http\Request;

echo "=== اختبار النظام المحدث لجلب الأساتذة والمواد ===\n\n";

$controller = new EmploiTempsController();

// اختبار 1: جلب الأساتذة للفصل الأول والفصل الدراسي الأول
echo "1. اختبار جلب الأساتذة:\n";
$request1 = new Request([
    'class_id' => 1,  // L1 Info A
    'trimester_id' => 1  // S1
]);

try {
    $response1 = $controller->getTeachers($request1);
    $data1 = json_decode($response1->getContent(), true);

    echo "عدد الأساتذة المتاحين: " . count($data1['data']) . "\n";

    foreach ($data1['data'] as $teacher) {
        echo "- {$teacher['full_name']}\n";
        foreach ($teacher['subjects'] as $subject) {
            echo "  * {$subject['name']} (معامل: {$subject['coefficient']})\n";
        }
    }

    // اختبار 2: جلب المواد لأول أستاذ
    if (!empty($data1['data'])) {
        echo "\n2. اختبار جلب المواد للأستاذ الأول:\n";
        $firstTeacher = $data1['data'][0];
        echo "الأستاذ: {$firstTeacher['full_name']}\n";

        $request2 = new Request([
            'teacher_id' => $firstTeacher['id'],
            'class_id' => 1,
            'trimester_id' => 1
        ]);

        $response2 = $controller->getSubjects($request2);
        $data2 = json_decode($response2->getContent(), true);

        echo "عدد المواد المتاحة: " . count($data2['subjects']) . "\n";

        foreach ($data2['subjects'] as $subject) {
            echo "- {$subject['name']} (معامل: {$subject['coefficient']})";
            if (isset($subject['specialite_name'])) {
                echo " - {$subject['specialite_name']}";
            }
            echo "\n";
        }
    }

    // اختبار 3: اختبار فصل مختلف
    echo "\n3. اختبار فصل مختلف (M1 Math):\n";
    $request3 = new Request([
        'class_id' => 3,  // M1 Math
        'trimester_id' => 3  // S3
    ]);

    $response3 = $controller->getTeachers($request3);
    $data3 = json_decode($response3->getContent(), true);

    echo "عدد الأساتذة في M1 Math - S3: " . count($data3['data']) . "\n";

    foreach ($data3['data'] as $teacher) {
        echo "- {$teacher['full_name']}\n";
        foreach ($teacher['subjects'] as $subject) {
            echo "  * {$subject['name']}\n";
        }
    }

} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . "\n";
}

echo "\n=== انتهى الاختبار ===\n";
