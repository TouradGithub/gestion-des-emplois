<?php
// test_api_schedule_data.php
// اختبار مباشر لـ API بيانات الجدول الزمني

echo "<h2>🧪 اختبار API بيانات الجدول الزمني</h2>";

$baseUrl = "http://172.20.10.4:8000/api/student";
$testNNI = "1234567890";
$testPassword = "1234567890";

// 1. تسجيل الدخول
echo "<h3>1. تسجيل الدخول:</h3>";
$loginData = json_encode([
    'nni' => $testNNI,
    'password' => $testPassword
]);

$curlLogin = curl_init();
curl_setopt_array($curlLogin, [
    CURLOPT_URL => $baseUrl . "/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $loginData,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ]
]);

$loginResponse = curl_exec($curlLogin);
$loginHttpCode = curl_getinfo($curlLogin, CURLINFO_HTTP_CODE);
curl_close($curlLogin);

if ($loginHttpCode != 200) {
    echo "<p style='color: red;'>❌ فشل تسجيل الدخول - كود: {$loginHttpCode}</p>";
    echo "<p>" . htmlspecialchars($loginResponse) . "</p>";
    exit;
}

$loginResult = json_decode($loginResponse, true);
if (!isset($loginResult['success']) || !$loginResult['success']) {
    echo "<p style='color: red;'>❌ فشل تسجيل الدخول</p>";
    echo "<pre>" . print_r($loginResult, true) . "</pre>";
    exit;
}

$token = $loginResult['data']['token'];
$studentName = $loginResult['data']['student']['fullname'];
$className = $loginResult['data']['student']['class']['nom'];

echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
echo "<p style='color: green;'><strong>✅ تم تسجيل الدخول بنجاح</strong></p>";
echo "<p><strong>الطالب:</strong> {$studentName}</p>";
echo "<p><strong>الصف:</strong> {$className}</p>";
echo "</div>";

// 2. جلب بيانات الجدول
echo "<h3>2. جلب بيانات الجدول الزمني:</h3>";

$curlSchedule = curl_init();
curl_setopt_array($curlSchedule, [
    CURLOPT_URL => $baseUrl . "/schedule/data",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]
]);

$scheduleResponse = curl_exec($curlSchedule);
$scheduleHttpCode = curl_getinfo($curlSchedule, CURLINFO_HTTP_CODE);
curl_close($curlSchedule);

if ($scheduleHttpCode != 200) {
    echo "<p style='color: red;'>❌ فشل جلب الجدول - كود: {$scheduleHttpCode}</p>";
    echo "<p>" . htmlspecialchars($scheduleResponse) . "</p>";
    exit;
}

$scheduleResult = json_decode($scheduleResponse, true);

if (!isset($scheduleResult['success']) || !$scheduleResult['success']) {
    echo "<p style='color: red;'>❌ فشل جلب الجدول</p>";
    echo "<pre>" . print_r($scheduleResult, true) . "</pre>";
    exit;
}

echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
echo "<p style='color: green;'><strong>✅ تم جلب بيانات الجدول بنجاح</strong></p>";
echo "</div>";

$data = $scheduleResult['data'];

// 3. عرض معلومات الطالب
echo "<h3>3. معلومات الطالب:</h3>";
echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
echo "<p><strong>الاسم:</strong> " . $data['student']['fullname'] . "</p>";
echo "<p><strong>NNI:</strong> " . $data['student']['nni'] . "</p>";
echo "<p><strong>الصف:</strong> " . $data['class_info']['nom'] . "</p>";
echo "<p><strong>المستوى:</strong> " . ($data['class_info']['niveau'] ?? 'غير محدد') . "</p>";
echo "<p><strong>التخصص:</strong> " . ($data['class_info']['specialite'] ?? 'غير محدد') . "</p>";
echo "</div>";

// 4. البحث عن حصة الاثنين 8-9
echo "<h3>4. البحث عن حصة الاثنين 8-9:</h3>";

$foundMonday89 = false;
$monday89Data = null;

foreach ($data['schedule_matrix'] as $timeSlot) {
    $timeLabel = $timeSlot['time_info']['libelle_ar'];
    $timeDebut = $timeSlot['time_info']['heure_debut'] ?? '';

    // التحقق من الوقت 8-9
    $is8to9 = (strpos($timeLabel, '8') !== false && strpos($timeLabel, '9') !== false) ||
              (strpos($timeDebut, '08:00') !== false);

    if ($is8to9) {
        foreach ($timeSlot['classes'] as $classDay) {
            $dayLabel = $classDay['day_info']['libelle_ar'];

            // التحقق من يوم الاثنين
            $isMonday = (strpos($dayLabel, 'الاثنين') !== false) ||
                       (strpos($dayLabel, 'Lundi') !== false);

            if ($isMonday && $classDay['class_data']['has_class']) {
                $foundMonday89 = true;
                $monday89Data = [
                    'time' => $timeLabel,
                    'time_details' => $timeDebut . ' - ' . ($timeSlot['time_info']['heure_fin'] ?? ''),
                    'day' => $dayLabel,
                    'subject' => $classDay['class_data']['subject'],
                    'teacher' => $classDay['class_data']['teacher']
                ];
                break 2;
            } elseif ($isMonday) {
                $monday89Data = [
                    'time' => $timeLabel,
                    'time_details' => $timeDebut . ' - ' . ($timeSlot['time_info']['heure_fin'] ?? ''),
                    'day' => $dayLabel,
                    'empty' => true
                ];
            }
        }
    }
}

if ($foundMonday89) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; border: 2px solid #28a745;'>";
    echo "<p style='color: green; font-size: 18px;'><strong>✅ تم العثور على الحصة!</strong></p>";
    echo "<p><strong>اليوم:</strong> " . $monday89Data['day'] . "</p>";
    echo "<p><strong>الوقت:</strong> " . $monday89Data['time'] . " (" . $monday89Data['time_details'] . ")</p>";
    echo "<p><strong>المادة:</strong> " . $monday89Data['subject'] . "</p>";
    echo "<p><strong>الأستاذ:</strong> " . $monday89Data['teacher'] . "</p>";
    echo "</div>";
} elseif ($monday89Data && isset($monday89Data['empty'])) {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; border: 2px solid #ffc107;'>";
    echo "<p style='color: orange; font-size: 18px;'><strong>⚠️ الحصة فارغة</strong></p>";
    echo "<p><strong>اليوم:</strong> " . $monday89Data['day'] . "</p>";
    echo "<p><strong>الوقت:</strong> " . $monday89Data['time'] . " (" . $monday89Data['time_details'] . ")</p>";
    echo "<p>لا توجد حصة مجدولة في هذا الوقت</p>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; border: 2px solid #dc3545;'>";
    echo "<p style='color: red; font-size: 18px;'><strong>❌ لم يتم العثور على الحصة</strong></p>";
    echo "<p>لم يتم العثور على يوم الاثنين في الوقت 8-9</p>";
    echo "</div>";
}

// 5. عرض جدول كامل
echo "<h3>5. الجدول الزمني الكامل:</h3>";
echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
echo "<thead><tr style='background: #f8f9fa;'>";
echo "<th style='border: 1px solid #ddd; padding: 8px;'>الوقت</th>";

foreach ($data['jours'] as $jour) {
    echo "<th style='border: 1px solid #ddd; padding: 8px;'>{$jour['libelle_ar']}</th>";
}
echo "</tr></thead><tbody>";

foreach ($data['schedule_matrix'] as $timeSlot) {
    echo "<tr>";
    echo "<td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>";
    echo $timeSlot['time_info']['libelle_ar'] . "<br>";
    echo "<small>" . ($timeSlot['time_info']['heure_debut'] ?? '') . " - " . ($timeSlot['time_info']['heure_fin'] ?? '') . "</small>";
    echo "</td>";

    foreach ($timeSlot['classes'] as $classDay) {
        echo "<td style='border: 1px solid #ddd; padding: 8px;'>";
        if ($classDay['class_data']['has_class']) {
            echo "<div style='background: #d4edda; padding: 5px; border-radius: 3px;'>";
            echo "<strong>" . $classDay['class_data']['subject'] . "</strong><br>";
            echo "<small>" . $classDay['class_data']['teacher'] . "</small>";
            echo "</div>";
        } else {
            echo "<span style='color: #999;'>فارغ</span>";
        }
        echo "</td>";
    }
    echo "</tr>";
}
echo "</tbody></table>";

// 6. إحصائيات
echo "<h3>6. إحصائيات:</h3>";
$totalClasses = 0;
$totalEmpty = 0;

foreach ($data['schedule_matrix'] as $timeSlot) {
    foreach ($timeSlot['classes'] as $classDay) {
        if ($classDay['class_data']['has_class']) {
            $totalClasses++;
        } else {
            $totalEmpty++;
        }
    }
}

echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
echo "<p><strong>عدد الأوقات:</strong> " . count($data['horaires']) . "</p>";
echo "<p><strong>عدد الأيام:</strong> " . count($data['jours']) . "</p>";
echo "<p><strong>إجمالي الخانات:</strong> " . (count($data['horaires']) * count($data['jours'])) . "</p>";
echo "<p><strong>الحصص المجدولة:</strong> <span style='color: green;'>{$totalClasses}</span></p>";
echo "<p><strong>الخانات الفارغة:</strong> <span style='color: orange;'>{$totalEmpty}</span></p>";
echo "</div>";

echo "<hr>";
echo "<h3>📋 ملخص الاختبار:</h3>";
echo "<ul>";
echo "<li>✅ API يعمل بشكل صحيح</li>";
echo "<li>✅ البيانات ترجع بتنسيق صحيح</li>";
echo "<li>" . ($foundMonday89 ? "✅ حصة الاثنين 8-9 موجودة" : "⚠️ حصة الاثنين 8-9 غير موجودة أو فارغة") . "</li>";
echo "</ul>";
?>
