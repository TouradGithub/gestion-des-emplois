<?php
// test_schedule_data_api.php
// اختبار سريع لـ endpoint بيانات الجدول الزمني الجديد

echo "<h2>اختبار API بيانات الجدول الزمني - Schedule Data API Test</h2>";

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
        
        echo "<h3>2. اختبار جلب بيانات الجدول المنسقة:</h3>";
        
        // اختبار endpoint الجديد
        $scheduleDataCurl = curl_init();
        curl_setopt_array($scheduleDataCurl, [
            CURLOPT_URL => $baseUrl . "/schedule/data",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]
        ]);

        $scheduleDataResponse = curl_exec($scheduleDataCurl);
        $scheduleDataHttpCode = curl_getinfo($scheduleDataCurl, CURLINFO_HTTP_CODE);
        curl_close($scheduleDataCurl);

        echo "<p><strong>رابط بيانات الجدول:</strong> {$baseUrl}/schedule/data</p>";
        echo "<p><strong>كود الاستجابة:</strong> {$scheduleDataHttpCode}</p>";

        if ($scheduleDataHttpCode == 200) {
            $scheduleDataResult = json_decode($scheduleDataResponse, true);
            if (isset($scheduleDataResult['success']) && $scheduleDataResult['success']) {
                echo "<p style='color: green;'><strong>✅ تم جلب بيانات الجدول بنجاح!</strong></p>";
                
                $data = $scheduleDataResult['data'];
                
                // عرض معلومات الطالب
                echo "<h4>معلومات الطالب:</h4>";
                echo "<ul>";
                echo "<li><strong>الاسم:</strong> " . $data['student']['fullname'] . "</li>";
                echo "<li><strong>NNI:</strong> " . $data['student']['nni'] . "</li>";
                echo "<li><strong>الصورة:</strong> " . ($data['student']['image'] ?? 'غير محددة') . "</li>";
                echo "</ul>";
                
                // عرض معلومات الصف
                echo "<h4>معلومات الصف:</h4>";
                echo "<ul>";
                echo "<li><strong>الاسم:</strong> " . $data['class_info']['nom'] . "</li>";
                echo "<li><strong>المستوى:</strong> " . ($data['class_info']['niveau'] ?? 'غير محدد') . "</li>";
                echo "<li><strong>التخصص:</strong> " . ($data['class_info']['specialite'] ?? 'غير محدد') . "</li>";
                echo "</ul>";
                
                // عرض إحصائيات الجدول
                echo "<h4>إحصائيات الجدول:</h4>";
                echo "<ul>";
                echo "<li><strong>عدد الأوقات:</strong> " . count($data['horaires']) . "</li>";
                echo "<li><strong>عدد الأيام:</strong> " . count($data['jours']) . "</li>";
                echo "<li><strong>عدد صفوف المصفوفة:</strong> " . count($data['schedule_matrix']) . "</li>";
                echo "</ul>";
                
                // عرض الأوقات
                echo "<h4>الأوقات المتاحة:</h4>";
                echo "<ol>";
                foreach ($data['horaires'] as $horaire) {
                    echo "<li>" . $horaire['libelle_ar'] . " (" . $horaire['libelle_fr'] . ")</li>";
                }
                echo "</ol>";
                
                // عرض الأيام
                echo "<h4>الأيام:</h4>";
                echo "<ol>";
                foreach ($data['jours'] as $jour) {
                    echo "<li>" . $jour['libelle_ar'] . " (" . $jour['libelle_fr'] . ")</li>";
                }
                echo "</ol>";
                
                // عرض نموذج من مصفوفة الجدول
                echo "<h4>نموذج من مصفوفة الجدول:</h4>";
                if (!empty($data['schedule_matrix'])) {
                    $firstRow = $data['schedule_matrix'][0];
                    echo "<p><strong>الوقت:</strong> " . $firstRow['time_info']['libelle_ar'] . "</p>";
                    echo "<p><strong>الحصص في هذا الوقت:</strong></p>";
                    echo "<ul>";
                    foreach ($firstRow['classes'] as $class) {
                        if (isset($class['class_data']['has_class']) && $class['class_data']['has_class']) {
                            echo "<li>" . $class['day_info']['libelle_ar'] . ": " . 
                                 $class['class_data']['subject'] . " - " . 
                                 $class['class_data']['teacher'] . "</li>";
                        } else {
                            echo "<li>" . $class['day_info']['libelle_ar'] . ": فارغ</li>";
                        }
                    }
                    echo "</ul>";
                }
                
            } else {
                echo "<p style='color: red;'><strong>❌ فشل في جلب بيانات الجدول</strong></p>";
                echo "<p><strong>رسالة الخطأ:</strong> " . ($scheduleDataResult['message'] ?? 'خطأ غير معروف') . "</p>";
            }
        } else {
            echo "<p style='color: red;'><strong>❌ خطأ في الاتصال بـ API بيانات الجدول</strong></p>";
            echo "<p><strong>رسالة الخطأ:</strong> " . $scheduleDataResponse . "</p>";
        }

        echo "<h3>3. مقارنة مع API الجدول العادي:</h3>";
        
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

        echo "<p><strong>رابط الجدول العادي:</strong> {$baseUrl}/schedule</p>";
        echo "<p><strong>كود الاستجابة:</strong> {$scheduleHttpCode}</p>";

        if ($scheduleHttpCode == 200) {
            $scheduleResult = json_decode($scheduleResponse, true);
            if (isset($scheduleResult['success']) && $scheduleResult['success']) {
                echo "<p style='color: green;'><strong>✅ الجدول العادي يعمل أيضاً!</strong></p>";
                $schedule = $scheduleResult['data']['schedule'];
                echo "<p><strong>عدد الأيام في الجدول العادي:</strong> " . count($schedule) . "</p>";
            }
        }

    } else {
        echo "<p style='color: red;'><strong>❌ فشل في تسجيل الدخول</strong></p>";
        echo "<p><strong>رسالة الخطأ:</strong> " . ($loginResult['message'] ?? 'خطأ غير معروف') . "</p>";
    }
} else {
    echo "<p style='color: red;'><strong>❌ خطأ في الاتصال بـ API تسجيل الدخول</strong></p>";
    echo "<p><strong>كود الخطأ:</strong> {$loginHttpCode}</p>";
    echo "<p><strong>رسالة الخطأ:</strong> " . $loginResponse . "</p>";
}

echo "<hr>";
echo "<h3>ملخص الاختبارات:</h3>";
echo "<ul>";
echo "<li><strong>/schedule</strong> - الجدول العادي (مجموع حسب الأيام)</li>";
echo "<li><strong>/schedule/data</strong> - بيانات منسقة مع مصفوفة الجدول</li>";
echo "<li><strong>/schedule/pdf</strong> - تحميل PDF</li>";
echo "</ul>";

echo "<h3>الفروق الرئيسية:</h3>";
echo "<ul>";
echo "<li><strong>الجدول العادي:</strong> مجمّع حسب اليوم، مناسب للعرض التقليدي</li>";
echo "<li><strong>بيانات الجدول:</strong> مصفوفة منسقة مع معلومات الأوقات والأيام، مناسب للتقويمات</li>";
echo "<li><strong>PDF:</strong> ملف قابل للطباعة</li>";
echo "</ul>";
?>