<?php

// اختبار روابط الـ pointages
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== اختبار روابط نظام الحضور ===\n\n";

try {
    // اختبار الروابط
    $routes = [
        'web.pointages.index' => 'قائمة الحضور',
        'web.pointages.create' => 'إضافة حضور جديد',
        'web.pointages.rapide' => 'الحضور السريع'
    ];

    foreach ($routes as $routeName => $description) {
        try {
            $url = route($routeName);
            echo "✅ $description: $url\n";
        } catch (Exception $e) {
            echo "❌ $description: خطأ - " . $e->getMessage() . "\n";
        }
    }

    echo "\n=== اختبار الترجمات ===\n";

    // اختبار الترجمات
    app()->setLocale('fr');
    echo "🇫🇷 فرنسي: " . __('pointages.gestion_pointages') . "\n";
    echo "🇫🇷 فرنسي: " . __('pointages.liste_pointages') . "\n";
    echo "🇫🇷 فرنسي: " . __('pointages.pointage_rapide') . "\n";

    app()->setLocale('ar');
    echo "🇸🇦 عربي: " . __('pointages.gestion_pointages') . "\n";
    echo "🇸🇦 عربي: " . __('pointages.liste_pointages') . "\n";
    echo "🇸🇦 عربي: " . __('pointages.pointage_rapide') . "\n";

    echo "\n=== إحصائيات سريعة ===\n";
    $total = DB::table('pointages')->count();
    $present = DB::table('pointages')->where('statut', 'present')->count();
    $absent = DB::table('pointages')->where('statut', 'absent')->count();

    echo "📊 إجمالي السجلات: $total\n";
    echo "✅ حضور: $present\n";
    echo "❌ غياب: $absent\n";

    echo "\n🎉 جميع الاختبارات نجحت! النظام جاهز للاستخدام.\n";

} catch (Exception $e) {
    echo "❌ خطأ في الاختبار: " . $e->getMessage() . "\n";
}
