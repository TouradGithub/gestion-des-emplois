<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== فحص بيانات الأساتذة ===\n\n";

$teachers = \App\Models\Teacher::all();
echo "عدد الأساتذة: " . $teachers->count() . "\n\n";

foreach ($teachers as $teacher) {
    echo "ID: {$teacher->id}\n";
    echo "الاسم: " . ($teacher->nom ?? 'فارغ') . "\n";
    echo "اللقب: " . ($teacher->prenom ?? 'فارغ') . "\n";
    echo "Name: " . ($teacher->name ?? 'فارغ') . "\n";
    echo "الأعمدة المتاحة: " . implode(', ', array_keys($teacher->getAttributes())) . "\n";
    echo "---\n";
}

echo "\n=== انتهى الفحص ===\n";
?>
