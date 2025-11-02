<?php
// test_student_pdf_api.php
// اختبار سريع لـ API endpoint الخاص بـ PDF

echo "<h2>اختبار API للطلاب - تحميل PDF</h2>";

// معلومات الاختبار
$baseUrl = "http://172.20.10.4:8000/api/student";
$testNNI = "1234567890";
$testPassword = "1234567890";

echo "<h3>1. اختبار تسجيل الدخول:</h3>";

// تسجيل دخول للحصول على token
$loginData = json_encode([
    'nni' => $testNNI,
    'password' => $testPassword
]);

$loginCurl = curl_init();
curl_setopt_array($loginCurl, [
    CURLOPT_URL => $baseUrl . "/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $loginData,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ]
]);

$loginResponse = curl_exec($loginCurl);
$loginHttpCode = curl_getinfo($loginCurl, CURLINFO_HTTP_CODE);
curl_close($loginCurl);

echo "<p><strong>رابط تسجيل الدخول:</strong> {$baseUrl}/login</p>";
echo "<p><strong>كود الاستجابة:</strong> {$loginHttpCode}</p>";

if ($loginHttpCode == 200) {
    $loginResult = json_decode($loginResponse, true);

    if (isset($loginResult['success']) && $loginResult['success']) {
        $token = $loginResult['data']['token'];
        echo "<p style='color: green;'><strong>✅ تم تسجيل الدخول بنجاح!</strong></p>";
        echo "<p><strong>Token:</strong> " . substr($token, 0, 50) . "...</p>";

        echo "<h3>2. اختبار تحميل PDF:</h3>";

        // اختبار تحميل PDF
        $pdfCurl = curl_init();
        curl_setopt_array($pdfCurl, [
            CURLOPT_URL => $baseUrl . "/schedule/pdf",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]
        ]);

        $pdfResponse = curl_exec($pdfCurl);
        $pdfHttpCode = curl_getinfo($pdfCurl, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($pdfCurl, CURLINFO_CONTENT_TYPE);
        curl_close($pdfCurl);

        echo "<p><strong>رابط تحميل PDF:</strong> {$baseUrl}/schedule/pdf</p>";
        echo "<p><strong>كود الاستجابة:</strong> {$pdfHttpCode}</p>";
        echo "<p><strong>نوع المحتوى:</strong> {$contentType}</p>";

        if ($pdfHttpCode == 200 && strpos($contentType, 'application/pdf') !== false) {
            echo "<p style='color: green;'><strong>✅ تم إنشاء ملف PDF بنجاح!</strong></p>";
            echo "<p><strong>حجم الملف:</strong> " . strlen($pdfResponse) . " bytes</p>";

            // حفظ الملف لاختبار
            $filename = "test_student_schedule_" . date('Y-m-d_H-i-s') . ".pdf";
            file_put_contents($filename, $pdfResponse);
            echo "<p><strong>تم حفظ الملف:</strong> <a href='{$filename}' target='_blank'>{$filename}</a></p>";

        } else {
            echo "<p style='color: red;'><strong>❌ فشل في إنشاء ملف PDF</strong></p>";
            if ($pdfHttpCode != 200) {
                echo "<p><strong>رسالة الخطأ:</strong> " . $pdfResponse . "</p>";
            }
        }

        echo "<h3>3. اختبار الجدول العادي (JSON):</h3>";

        // اختبار الجدول العادي للمقارنة
        $scheduleCurl = curl_init();
        curl_setopt_array($scheduleCurl, [
            CURLOPT_URL => $baseUrl . "/schedule",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]
        ]);

        $scheduleResponse = curl_exec($scheduleCurl);
        $scheduleHttpCode = curl_getinfo($scheduleCurl, CURLINFO_HTTP_CODE);
        curl_close($scheduleCurl);

        echo "<p><strong>رابط الجدول JSON:</strong> {$baseUrl}/schedule</p>";
        echo "<p><strong>كود الاستجابة:</strong> {$scheduleHttpCode}</p>";

        if ($scheduleHttpCode == 200) {
            $scheduleResult = json_decode($scheduleResponse, true);
            if (isset($scheduleResult['success']) && $scheduleResult['success']) {
                echo "<p style='color: green;'><strong>✅ تم جلب الجدول بنجاح!</strong></p>";

                // عرض ملخص الجدول
                if (isset($scheduleResult['data']['schedule'])) {
                    $schedule = $scheduleResult['data']['schedule'];
                    echo "<p><strong>عدد الأيام:</strong> " . count($schedule) . "</p>";

                    $totalClasses = 0;
                    foreach ($schedule as $day => $classes) {
                        $totalClasses += count($classes);
                    }
                    echo "<p><strong>إجمالي الحصص:</strong> {$totalClasses}</p>";
                } else {
                    echo "<p style='color: orange;'><strong>⚠️ الجدول فارغ</strong></p>";
                }
            } else {
                echo "<p style='color: red;'><strong>❌ فشل في جلب الجدول</strong></p>";
            }
        } else {
            echo "<p style='color: red;'><strong>❌ خطأ في الاتصال بـ API الجدول</strong></p>";
            echo "<p><strong>رسالة الخطأ:</strong> " . $scheduleResponse . "</p>";
        }

    } else {
        echo "<p style='color: red;'><strong>❌ فشل في تسجيل الدخول</strong></p>";
        echo "<p><strong>رسالة الخطأ:</strong> " . $loginResponse . "</p>";
    }
} else {
    echo "<p style='color: red;'><strong>❌ خطأ في الاتصال بـ API تسجيل الدخول</strong></p>";
    echo "<p><strong>رسالة الخطأ:</strong> " . $loginResponse . "</p>";
}

echo "<hr>";
echo "<h3>ملاحظات مهمة:</h3>";
echo "<ul>";
echo "<li>تأكد من وجود طالب بـ NNI: {$testNNI}</li>";
echo "<li>تأكد من أن كلمة المرور هي: {$testPassword}</li>";
echo "<li>تأكد من أن الطالب مسجل في صف له جدول زمني</li>";
echo "<li>تأكد من تفعيل مكتبة mPDF</li>";
echo "<li>تأكد من صحة روابط الـ API</li>";
echo "</ul>";

echo "<h3>الروابط المختبرة:</h3>";
echo "<ul>";
echo "<li><strong>تسجيل الدخول:</strong> POST {$baseUrl}/login</li>";
echo "<li><strong>الجدول JSON:</strong> GET {$baseUrl}/schedule</li>";
echo "<li><strong>الجدول PDF:</strong> GET {$baseUrl}/schedule/pdf</li>";
echo "</ul>";
?>
