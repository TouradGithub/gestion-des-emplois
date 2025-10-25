<?php

echo "<h2>🧪 اختبار نظام الترجمة</h2>\n";

// تحميل Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// اختبار اللغة الحالية
echo "<h3>🌍 إعدادات اللغة:</h3>\n";
echo "اللغة الحالية: <strong>" . app()->getLocale() . "</strong><br>\n";
echo "اللغة الاحتياطية: <strong>" . config('app.fallback_locale') . "</strong><br>\n";

// اختبار ترجمات pointages
echo "<h3>📋 اختبار ترجمات الحضور:</h3>\n";
$pointages_keys = [
    'pointages.liste_pointages',
    'pointages.professeur',
    'pointages.date_pointage',
    'pointages.cours',
    'pointages.classe',
    'pointages.horaires',
    'pointages.statut',
    'pointages.heure_arrivee'
];

foreach ($pointages_keys as $key) {
    $translation = __($key);
    if ($translation === $key) {
        echo "❌ {$key} → <span style='color: red;'>غير مترجم!</span><br>\n";
    } else {
        echo "✅ {$key} → <strong>{$translation}</strong><br>\n";
    }
}

// اختبار ترجمات messages
echo "<h3>💬 اختبار ترجمات العامة:</h3>\n";
$messages_keys = [
    'messages.actions',
    'messages.edit',
    'messages.delete',
    'messages.view',
    'messages.dashboard'
];

foreach ($messages_keys as $key) {
    $translation = __($key);
    if ($translation === $key) {
        echo "❌ {$key} → <span style='color: red;'>غير مترجم!</span><br>\n";
    } else {
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
