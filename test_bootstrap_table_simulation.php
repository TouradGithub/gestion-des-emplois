<?php
/**
 * محاكاة طلب Bootstrap Table تماماً
 */

// تحميل Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\SubjectTeacherController;
use Illuminate\Http\Request;

echo "=== محاكاة طلب Bootstrap Table ===\n\n";

// محاكاة نفس المعاملات التي يرسلها Bootstrap Table
$request = new Request([
    'offset' => 0,
    'limit' => 10,
    'sort' => 'id',
    'order' => 'desc',
    'search' => '',
    '_' => time() // timestamp parameter that bootstrap-table often sends
]);

echo "معاملات الطلب:\n";
echo "- offset: " . $request->get('offset') . "\n";
echo "- limit: " . $request->get('limit') . "\n";
echo "- sort: " . $request->get('sort') . "\n";
echo "- order: " . $request->get('order') . "\n\n";

try {
    $controller = new SubjectTeacherController();
    $response = $controller->listSubjectTeacher($request);

    $data = json_decode($response->getContent(), true);

    echo "استجابة الخادم:\n";
    echo "- إجمالي السجلات: " . $data['total'] . "\n";
    echo "- عدد السجلات المعروضة: " . count($data['rows']) . "\n\n";

    echo "عينة من البيانات:\n";
    if (!empty($data['rows'])) {
        $firstRow = $data['rows'][0];
        echo "أول سجل:\n";
        foreach ($firstRow as $key => $value) {
            if ($key !== 'operate') { // skip operate field for cleaner output
                echo "  {$key}: {$value}\n";
            }
        }

        // التحقق من وجود حقل classe
        if (isset($firstRow['classe'])) {
            echo "\n✅ حقل 'classe' موجود في البيانات: " . $firstRow['classe'] . "\n";
        } else {
            echo "\n❌ حقل 'classe' غير موجود في البيانات\n";
        }
    }

    // عرض JSON formatted للتأكد
    echo "\n=== البيانات بصيغة JSON المنسقة ===\n";
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . "\n";
}

echo "\n=== انتهى الاختبار ===\n";
