<?php
// اختبار سريع لفحص مسارات الحضور

echo "<h2>🧪 اختبار مسارات نظام الحضور</h2>\n";

// فحص وجود المسارات باستخدام helper function
$routes_to_test = [
    'web.pointages.index',
    'web.pointages.create',
    'web.pointages.store',
    'web.pointages.show',
    'web.pointages.edit',
    'web.pointages.update',
    'web.pointages.destroy',
    'web.pointages.rapide',
    'web.pointages.store-rapide',
    'web.pointages.get-emplois'
];

$success = true;

foreach ($routes_to_test as $route_name) {
    try {
        // محاولة إنشاء URL للمسار
        if (in_array($route_name, ['web.pointages.show', 'web.pointages.edit', 'web.pointages.update', 'web.pointages.destroy'])) {
            // مسارات تحتاج معرف
            $url = route($route_name, 1);
        } else {
            // مسارات عادية
            $url = route($route_name);
        }
        echo "✅ {$route_name} → {$url}<br>\n";
    } catch (Exception $e) {
        echo "❌ {$route_name} → خطأ: {$e->getMessage()}<br>\n";
        $success = false;
    }
}

echo "<h3>📊 النتيجة:</h3>\n";
if ($success) {
    echo "<div style='background: #d4edda; color: #155724; padding: 10px; border-radius: 5px;'>";
    echo "🎉 <strong>ممتاز!</strong> جميع مسارات نظام الحضور تعمل بشكل صحيح!<br>";
    echo "يمكنك الآن الوصول إلى النظام من القائمة الجانبية.";
    echo "</div>\n";
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;'>";
    echo "⚠️ <strong>تحذير!</strong> هناك مشاكل في بعض المسارات.";
    echo "</div>\n";
}

echo "<h3>🔗 روابط للاختبار:</h3>\n";
echo "<ul>\n";
echo "<li><a href='/admin/pointages' target='_blank'>📋 إدارة الحضور</a></li>\n";
echo "<li><a href='/admin/pointages/create' target='_blank'>➕ إضافة حضور</a></li>\n";
echo "<li><a href='/admin/pointages/rapide/aujourd-hui' target='_blank'>⚡ تسجيل سريع</a></li>\n";
echo "</ul>\n";

?>
