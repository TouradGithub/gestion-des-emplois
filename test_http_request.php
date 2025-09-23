<?php
/**
 * محاكاة طلب HTTP فعلي لراوت get-teachers
 */

// تحديد URL الأساسي
$baseUrl = 'http://localhost/gestion-des-emplois'; // غير هذا حسب الإعداد المحلي

// بناء URL الكامل
$url = $baseUrl . '/admin/emplois/get-teachers?class_id=1&trimester_id=2';

echo "=== اختبار طلب HTTP فعلي ===\n\n";
echo "الـ URL: $url\n\n";

// إرسال طلب HTTP
$response = file_get_contents($url);

if ($response === false) {
    echo "فشل في إرسال الطلب. تأكد من:\n";
    echo "1. أن الخادم يعمل\n";
    echo "2. أن الـ URL صحيح\n";
    echo "3. أن Laravel يعمل بشكل صحيح\n";
} else {
    echo "الاستجابة الكاملة:\n";
    echo $response . "\n\n";

    $data = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "تحليل JSON:\n";
        echo "- يحتوي على 'data': " . (isset($data['data']) ? 'نعم' : 'لا') . "\n";
        echo "- يحتوي على 'total': " . (isset($data['total']) ? 'نعم' : 'لا') . "\n";
        echo "- يحتوي على 'rows': " . (isset($data['rows']) ? 'نعم' : 'لا') . "\n";

        if (isset($data['data'])) {
            echo "- عدد العناصر في data: " . count($data['data']) . "\n";
        }
        if (isset($data['rows'])) {
            echo "- عدد العناصر في rows: " . count($data['rows']) . "\n";
        }
    } else {
        echo "خطأ في تحليل JSON: " . json_last_error_msg() . "\n";
    }
}

echo "\n=== انتهى الاختبار ===\n";
?>
