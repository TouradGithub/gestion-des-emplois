<?php
echo "🔍 فحص ملفات البلايد...\n\n";

$bladeFiles = [
    'resources/views/teacher/departments.blade.php',
    'resources/views/teacher/dashboard.blade.php'
];

foreach ($bladeFiles as $file) {
    echo "📂 فحص: $file\n";
    
    if (!file_exists($file)) {
        echo "❌ الملف غير موجود!\n\n";
        continue;
    }
    
    $content = file_get_contents($file);
    
    // فحص الأخطاء الشائعة
    $errors = [];
    
    // فحص @if و @endif
    $ifCount = substr_count($content, '@if');
    $endifCount = substr_count($content, '@endif');
    if ($ifCount !== $endifCount) {
        $errors[] = "عدم تطابق @if و @endif ($ifCount vs $endifCount)";
    }
    
    // فحص @foreach و @endforeach
    $foreachCount = substr_count($content, '@foreach');
    $endforeachCount = substr_count($content, '@endforeach');
    if ($foreachCount !== $endforeachCount) {
        $errors[] = "عدم تطابق @foreach و @endforeach ($foreachCount vs $endforeachCount)";
    }
    
    // فحص التكرار في @extends
    $extendsCount = substr_count($content, '@extends');
    if ($extendsCount > 1) {
        $errors[] = "تكرار في @extends ($extendsCount مرات)";
    }
    
    if (empty($errors)) {
        echo "✅ الملف سليم!\n\n";
    } else {
        echo "❌ أخطاء موجودة:\n";
        foreach ($errors as $error) {
            echo "   - $error\n";
        }
        echo "\n";
    }
}

echo "🎯 انتهى الفحص!\n";
?>