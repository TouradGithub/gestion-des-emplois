<?php
// api_connection_test.php
// اختبار اتصال API لحل مشكلة الروابط

echo "<h1>🔧 اختبار اتصال API - حل مشكلة الروابط</h1>";

$baseUrl = "http://172.20.10.4:8000";
$testData = [
    'nni' => '1234567890',
    'password' => '1234567890'
];

echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<h2>🚨 المشكلة المحددة:</h2>";
echo "<p><strong>التطبيق يرسل إلى:</strong> <code>POST http://172.20.10.4:8000/student/login</code></p>";
echo "<p><strong>والصحيح هو:</strong> <code>POST http://172.20.10.4:8000/api/student/login</code></p>";
echo "<p><strong>المشكلة:</strong> نقص <code>/api</code> في المسار</p>";
echo "</div>";

echo "<h3>1️⃣ اختبار المسار الخاطئ (الذي يستخدمه التطبيق):</h3>";
$wrongUrl = $baseUrl . "/student/login";
echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107;'>";
echo "<p><strong>🔗 الرابط المختبر:</strong> {$wrongUrl}</p>";

$curl1 = curl_init();
curl_setopt_array($curl1, [
    CURLOPT_URL => $wrongUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($testData),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ],
    CURLOPT_TIMEOUT => 10
]);

$response1 = curl_exec($curl1);
$httpCode1 = curl_getinfo($curl1, CURLINFO_HTTP_CODE);
$error1 = curl_error($curl1);
curl_close($curl1);

echo "<p><strong>📊 كود الاستجابة:</strong> {$httpCode1}</p>";
if ($error1) {
    echo "<p><strong>❌ خطأ cURL:</strong> {$error1}</p>";
}
if ($httpCode1 == 404) {
    echo "<p style='color: red; font-weight: bold;'>❌ الرابط غير موجود (404) - هذا هو سبب المشكلة!</p>";
} else {
    echo "<p><strong>📝 الاستجابة:</strong> " . htmlspecialchars(substr($response1, 0, 300)) . "</p>";
}
echo "</div>";

echo "<hr style='margin: 30px 0;'>";

echo "<h3>2️⃣ اختبار المسار الصحيح:</h3>";
$correctUrl = $baseUrl . "/api/student/login";
echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745;'>";
echo "<p><strong>🔗 الرابط الصحيح:</strong> {$correctUrl}</p>";

$curl2 = curl_init();
curl_setopt_array($curl2, [
    CURLOPT_URL => $correctUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($testData),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ],
    CURLOPT_TIMEOUT => 10
]);

$response2 = curl_exec($curl2);
$httpCode2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
$error2 = curl_error($curl2);
curl_close($curl2);

echo "<p><strong>📊 كود الاستجابة:</strong> {$httpCode2}</p>";
if ($error2) {
    echo "<p><strong>❌ خطأ cURL:</strong> {$error2}</p>";
}

if ($httpCode2 == 200) {
    echo "<p style='color: green; font-weight: bold;'>✅ نجح الاتصال!</p>";
    $result = json_decode($response2, true);
    if (isset($result['success'])) {
        echo "<p><strong>✅ حالة النجاح:</strong> " . ($result['success'] ? 'true' : 'false') . "</p>";
        echo "<p><strong>💬 الرسالة:</strong> " . ($result['message'] ?? 'لا توجد رسالة') . "</p>";
        if (isset($result['data']['student']['fullname'])) {
            echo "<p><strong>👤 اسم الطالب:</strong> " . $result['data']['student']['fullname'] . "</p>";
        }
    }
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ فشل في الاتصال - كود: {$httpCode2}</p>";
    echo "<p><strong>📝 الاستجابة:</strong> " . htmlspecialchars(substr($response2, 0, 500)) . "</p>";
}
echo "</div>";

echo "<hr style='margin: 30px 0;'>";

echo "<h3>3️⃣ فحص جميع endpoints:</h3>";
$routes = [
    "/api/student/login" => "POST",
    "/api/student/profile" => "GET",
    "/api/student/schedule" => "GET",
    "/api/student/schedule/data" => "GET",
    "/api/student/schedule/pdf" => "GET",
    "/api/student/logout" => "POST"
];

echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
echo "<tr style='background: #f8f9fa;'>";
echo "<th style='border: 1px solid #ddd; padding: 8px;'>Method</th>";
echo "<th style='border: 1px solid #ddd; padding: 8px;'>Endpoint</th>";
echo "<th style='border: 1px solid #ddd; padding: 8px;'>Status</th>";
echo "</tr>";

foreach ($routes as $route => $method) {
    $fullUrl = $baseUrl . $route;

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $fullUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
        CURLOPT_TIMEOUT => 5
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    echo "<tr>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'><strong>{$method}</strong></td>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'><code>{$route}</code></td>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'>";

    if ($httpCode == 401) {
        echo "<span style='color: orange;'>🔒 محمي (يحتاج token)</span>";
    } elseif ($httpCode == 404) {
        echo "<span style='color: red;'>❌ غير موجود</span>";
    } elseif ($httpCode == 200) {
        echo "<span style='color: green;'>✅ متاح</span>";
    } elseif ($httpCode == 405) {
        echo "<span style='color: blue;'>🔵 Method غير مسموح</span>";
    } else {
        echo "<span style='color: gray;'>🔵 كود {$httpCode}</span>";
    }

    echo "</td></tr>";
}
echo "</table>";

echo "<hr style='margin: 30px 0;'>";

echo "<div style='background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 20px; margin: 20px 0;'>";
echo "<h3>💡 الحل المطلوب للتطبيق:</h3>";
echo "<h4>🔧 تحديث Base URL في التطبيق:</h4>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 4px; font-family: monospace;'>";
echo "// ❌ الإعداد الخاطئ الحالي:<br>";
echo "const BASE_URL = 'http://172.20.10.4:8000';<br><br>";
echo "// ✅ الإعداد الصحيح المطلوب:<br>";
echo "const BASE_URL = 'http://172.20.10.4:8000/api';<br><br>";
echo "// أو استخدام:<br>";
echo "const API_BASE_URL = 'http://172.20.10.4:8000/api';<br>";
echo "const ENDPOINTS = {<br>";
echo "&nbsp;&nbsp;LOGIN: '/student/login',<br>";
echo "&nbsp;&nbsp;PROFILE: '/student/profile',<br>";
echo "&nbsp;&nbsp;SCHEDULE: '/student/schedule'<br>";
echo "};";
echo "</div>";

echo "<h4>📱 أمثلة للاستخدام:</h4>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 4px; font-family: monospace;'>";
echo "// JavaScript/React Native<br>";
echo "const loginUrl = BASE_URL + '/student/login';<br>";
echo "// النتيجة: http://172.20.10.4:8000/api/student/login<br><br>";
echo "// Dart/Flutter<br>";
echo "static const String baseUrl = 'http://172.20.10.4:8000/api';<br>";
echo "final loginEndpoint = '\$baseUrl/student/login';<br>";
echo "</div>";
echo "</div>";

echo "<div style='background: #fff3cd; border: 1px solid #ffeeba; border-radius: 8px; padding: 15px; margin: 20px 0;'>";
echo "<h4>⚠️ تأكد من:</h4>";
echo "<ul>";
echo "<li>✅ خادم Laravel يعمل على <code>http://172.20.10.4:8000</code></li>";
echo "<li>✅ ملف <code>routes/api.php</code> يحتوي على routes الصحيحة</li>";
echo "<li>✅ ملف <code>bootstrap/app.php</code> يحتوي على <code>withRouting</code></li>";
echo "<li>✅ التطبيق يستخدم <code>/api</code> في بداية كل endpoint</li>";
echo "</ul>";
echo "</div>";

echo "<hr>";
echo "<p style='text-align: center; color: #666; font-style: italic;'>";
echo "تم إنشاء هذا الاختبار في: " . date('Y-m-d H:i:s');
echo "</p>";
?>
