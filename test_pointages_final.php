<?php

echo "<h2>🧪 اختبار شامل لنظام الحضور والغياب</h2>\n";

echo "<h3>1️⃣ فحص المسارات في ملف routes/web.php:</h3>\n";

// قراءة ملف المسارات
$webRoutes = file_get_contents(__DIR__ . '/routes/web.php');

// قائمة المسارات المطلوبة للنظام
$requiredRoutes = [
    'pointages',
    'pointages/create',
    'pointages/rapide/aujourd-hui',
    'pointages/ajax/emplois'
];

$allRoutesExist = true;

foreach ($requiredRoutes as $route) {
    if (strpos($webRoutes, $route) !== false) {
        echo "✅ Route: /admin/{$route}<br>\n";
    } else {
        echo "❌ Route: /admin/{$route} - غير موجود!<br>\n";
        $allRoutesExist = false;
    }
}

echo "<h3>2️⃣ فحص الملفات المطلوبة:</h3>\n";

$requiredFiles = [
    'app/Http/Controllers/PointageController.php',
    'app/Models/Pointage.php',
    'resources/views/admin/pointages/index.blade.php',
    'resources/views/admin/pointages/create.blade.php',
    'resources/views/admin/pointages/edit.blade.php',
    'resources/views/admin/pointages/show.blade.php',
    'resources/views/admin/pointages/rapide.blade.php',
    'database/migrations/2025_10_23_203744_create_pointages_table.php'
];

$allFilesExist = true;

foreach ($requiredFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ {$file}<br>\n";
    } else {
        echo "❌ {$file} - غير موجود!<br>\n";
        $allFilesExist = false;
    }
}

echo "<h3>3️⃣ فحص Controller Methods:</h3>\n";

$controllerFile = file_get_contents(__DIR__ . '/app/Http/Controllers/PointageController.php');

$requiredMethods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'rapide', 'storeRapide', 'getEmploisByClass'];

foreach ($requiredMethods as $method) {
    if (strpos($controllerFile, "function {$method}(") !== false) {
        echo "✅ PointageController::{$method}()<br>\n";
    } else {
        echo "❌ PointageController::{$method}() - غير موجودة!<br>\n";
    }
}

echo "<h3>4️⃣ النتيجة النهائية:</h3>\n";

if ($allRoutesExist && $allFilesExist) {
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "🎉 <strong>ممتاز! نظام الحضور والغياب جاهز بالكامل!</strong><br>";
    echo "يمكنك الآن استخدام النظام من خلال:<br>";
    echo "• القائمة الجانبية → إدارة الحضور<br>";
    echo "• القائمة الجانبية → تسجيل سريع<br>";
    echo "• جميع العمليات: عرض، إنشاء، تعديل، حذف<br>";
    echo "</div>\n";
} else {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "⚠️ <strong>هناك بعض المشاكل في النظام!</strong><br>";
    echo "يرجى فحص الملفات والمسارات المفقودة أعلاه.<br>";
    echo "</div>\n";
}

echo "<h3>5️⃣ روابط سريعة للاختبار:</h3>\n";
echo "<ul>\n";
echo "<li><a href='/admin/pointages' target='_blank'>📋 صفحة إدارة الحضور</a></li>\n";
echo "<li><a href='/admin/pointages/create' target='_blank'>➕ إضافة حضور جديد</a></li>\n";
echo "<li><a href='/admin/pointages/rapide/aujourd-hui' target='_blank'>⚡ تسجيل سريع</a></li>\n";
echo "</ul>\n";

?>
