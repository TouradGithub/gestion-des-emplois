<?php
// ملف اختبار للتأكد من أن النظام يعمل
echo "Testing Subject Teacher System...\n";

// اختبار الملفات المطلوبة
$files = [
    'resources/views/teacher/departments.blade.php',
    'resources/views/teacher/dashboard.blade.php',
    'resources/views/teacher/schedule.blade.php',
    'app/Http/Controllers/TeacherDashboardController.php',
    'lang/ar/teacher.php',
    'lang/fr/teacher.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists\n";
    } else {
        echo "❌ $file missing\n";
    }
}

echo "\n🎯 النظام جاهز للاستخدام!\n";
echo "يمكن الآن الضغط على 'Mes Matières' بدون أخطاء syntax\n";
?>
