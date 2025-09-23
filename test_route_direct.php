<?php
/**
 * اختبار الراوت مباشرة عبر Laravel
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار مباشر للراوت ===\n\n";

// محاكاة HTTP Request
$uri = '/admin/emplois/get-teachers?class_id=1&trimester_id=2';
$request = \Illuminate\Http\Request::create($uri, 'GET', [
    'class_id' => 1,
    'trimester_id' => 2
]);

// تعيين الطلب في Laravel
$app->instance('request', $request);

try {
    // إنشاء مثيل من الكنترولر واستدعاء الدالة
    $controller = new \App\Http\Controllers\EmploiTempsController();
    $response = $controller->getTeachers($request);

    echo "نتيجة الاستدعاء المباشر:\n";
    echo $response->getContent() . "\n\n";

    $data = json_decode($response->getContent(), true);
    if (isset($data['data'])) {
        echo "✅ تم العثور على " . count($data['data']) . " أستاذ\n";
        foreach ($data['data'] as $teacher) {
            echo "- {$teacher['full_name']}\n";
        }
    } else {
        echo "❌ لم يتم العثور على بيانات\n";
    }

} catch (\Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}

echo "\n=== انتهى الاختبار ===\n";
?>
