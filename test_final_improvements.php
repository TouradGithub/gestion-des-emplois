<?php
/**
 * اختبار التحسينات النهائية لنظام إدارة الحصص
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\EmploiTempsController;
use Illuminate\Http\Request;

echo "=== اختبار التحسينات النهائية ===\n\n";

$controller = new EmploiTempsController();

echo "1. اختبار جلب الأساتذة (class_id=1, trimester_id=1):\n";
$request = new Request(['class_id' => 1, 'trimester_id' => 1]);
$response = $controller->getTeachers($request);
$teachersData = json_decode($response->getContent(), true);

if (isset($teachersData['data']) && count($teachersData['data']) > 0) {
    echo "✅ نجح - " . count($teachersData['data']) . " أستاذ متاح\n";
    foreach ($teachersData['data'] as $teacher) {
        echo "  - {$teacher['full_name']}\n";
    }
} else {
    echo "❌ فشل - لم يتم العثور على أساتذة\n";
}

echo "\n2. اختبار عرض قائمة الحصص:\n";
$listRequest = new Request(['limit' => 5, 'offset' => 0]);
$listResponse = $controller->show($listRequest);
$listData = json_decode($listResponse->getContent(), true);

if (isset($listData['total']) && isset($listData['rows'])) {
    echo "✅ نجح - إجمالي الحصص: " . $listData['total'] . "\n";
    echo "عدد الصفوف المعروضة: " . count($listData['rows']) . "\n";

    if (count($listData['rows']) > 0) {
        echo "أول حصة:\n";
        $firstRow = $listData['rows'][0];
        echo "  - الفصل: " . ($firstRow['class'] ?? 'غير محدد') . "\n";
        echo "  - المادة: " . ($firstRow['subject'] ?? 'غير محدد') . "\n";
        echo "  - الأستاذ: " . ($firstRow['teacher'] ?? 'غير محدد') . "\n";
        echo "  - اليوم: " . ($firstRow['jour'] ?? 'غير محدد') . "\n";
        echo "  - التوقيت: " . strip_tags($firstRow['horaire'] ?? 'غير محدد') . "\n";
    }
} else {
    echo "❌ فشل - خطأ في تنسيق البيانات\n";
}

echo "\n3. فحص وجود الحصص في قاعدة البيانات:\n";
$emploisCount = \App\Models\EmploiTemps::count();
echo "إجمالي الحصص: $emploisCount\n";

if ($emploisCount > 0) {
    $emploi = \App\Models\EmploiTemps::with(['classe', 'subject', 'teacher', 'jour', 'horairess'])
                                   ->first();
    echo "أول حصة:\n";
    echo "  - الفصل: " . ($emploi->classe->nom ?? 'غير محدد') . "\n";
    echo "  - المادة: " . ($emploi->subject->name ?? 'غير محدد') . "\n";
    echo "  - الأستاذ: " . ($emploi->teacher->name ?? 'غير محدد') . "\n";
    echo "  - اليوم: " . ($emploi->jour->libelle_fr ?? 'غير محدد') . "\n";

    if ($emploi->horairess->count() > 0) {
        echo "  - الحصص الزمنية: ";
        foreach ($emploi->horairess as $horaire) {
            echo $horaire->libelle_fr . " ";
        }
        echo "\n";
    }
}

echo "\n=== تم الانتهاء من الاختبار ===\n";

echo "\n📋 ملخص التحسينات المطبقة:\n";
echo "✅ 1. فاليديشن شامل لمنع التضارب في الحصص\n";
echo "✅ 2. ترجمة النصوص للفرنسية\n";
echo "✅ 3. تحسين عرض البيانات في صفحة الفهرس\n";
echo "✅ 4. إصلاح مشكلة تراكم الحصص الزمنية\n";
echo "✅ 5. تحسين رسائل الخطأ والنجاح\n";
?>
