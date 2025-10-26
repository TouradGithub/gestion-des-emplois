<?php

/**

 * اختبار نظام الترجمةecho "<h2>🧪 اختبار نظام الترجمة</h2>\n";

 */

// تحميل Laravel

// تحميل Laravelrequire_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/vendor/autoload.php';$app = require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();$kernel->bootstrap();



echo "=== اختبار نظام الترجمة ===\n\n";// اختبار اللغة الحالية

echo "<h3>🌍 إعدادات اللغة:</h3>\n";

// اختبار اللغة الحاليةecho "اللغة الحالية: <strong>" . app()->getLocale() . "</strong><br>\n";

echo "1. اللغة المضبوطة حالياً:\n";echo "اللغة الاحتياطية: <strong>" . config('app.fallback_locale') . "</strong><br>\n";

echo "App Locale: " . config('app.locale') . "\n";

echo "Current Locale: " . app()->getLocale() . "\n";// اختبار ترجمات pointages

echo "Fallback Locale: " . config('app.fallback_locale') . "\n\n";echo "<h3>📋 اختبار ترجمات الحضور:</h3>\n";

$pointages_keys = [

// اختبار الترجمات    'pointages.liste_pointages',

echo "2. اختبار الترجمات:\n";    'pointages.professeur',

    'pointages.date_pointage',

$translations = [    'pointages.cours',

    'messages.dashboard',    'pointages.classe',

    'messages.recent_teachers',    'pointages.horaires',

    'messages.quick_actions',    'pointages.statut',

    'messages.add_teacher',    'pointages.heure_arrivee'

    'messages.admin_dashboard_welcome'];

];

foreach ($pointages_keys as $key) {

foreach ($translations as $key) {    $translation = __($key);

    $translated = __($key);    if ($translation === $key) {

    echo "- {$key}: '{$translated}'\n";        echo "❌ {$key} → <span style='color: red;'>غير مترجم!</span><br>\n";

        } else {

    if ($translated === $key) {        echo "✅ {$key} → <strong>{$translation}</strong><br>\n";

        echo "  ⚠️ الترجمة مفقودة!\n";    }

    } else {}

        echo "  ✅ مترجم بنجاح\n";

    }// اختبار ترجمات messages

}echo "<h3>💬 اختبار ترجمات العامة:</h3>\n";

$messages_keys = [

echo "\n3. اختبار ملفات الترجمة:\n";    'messages.actions',

$langPath = resource_path('lang/fr/messages.php');    'messages.edit',

echo "مسار ملف الترجمة: {$langPath}\n";    'messages.delete',

echo "الملف موجود: " . (file_exists($langPath) ? 'نعم' : 'لا') . "\n";    'messages.view',

    'messages.dashboard'

if (file_exists($langPath)) {];

    $messages = include $langPath;

    echo "عدد المفاتيح: " . count($messages) . "\n";foreach ($messages_keys as $key) {

    echo "recent_teachers موجود: " . (isset($messages['recent_teachers']) ? 'نعم' : 'لا') . "\n";    $translation = __($key);

}    if ($translation === $key) {

        echo "❌ {$key} → <span style='color: red;'>غير مترجم!</span><br>\n";

echo "\n=== انتهى الاختبار ===\n";    } else {
        echo "✅ {$key} → <strong>{$translation}</strong><br>\n";
    }
}

echo "<h3>📁 اختبار وجود ملفات الترجمة:</h3>\n";
$lang_files = [
    'lang/ar/pointages.php',
    'lang/ar/messages.php'
];

foreach ($lang_files as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ {$file} موجود<br>\n";
    } else {
        echo "❌ {$file} غير موجود!<br>\n";
    }
}

?>
