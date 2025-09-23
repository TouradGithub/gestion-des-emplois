<?php
require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== فحص التخصصات والأقسام ===\n\n";

// فحص جدول الأقسام
echo "=== الأقسام والتخصصات ===\n";
$classes = \App\Models\Classe::with('specialite')->get();
foreach ($classes as $class) {
    echo "القسم: {$class->nom} (ID: {$class->id})\n";
    echo "specialite_id: {$class->specialite_id}\n";
    echo "التخصص: " . ($class->specialite ? $class->specialite->nom : 'لا يوجد') . "\n";
    echo "---\n";
}

echo "\n=== جميع التخصصات ===\n";
$specialities = \App\Models\Speciality::all();
foreach ($specialities as $speciality) {
    echo "ID: {$speciality->id} - {$speciality->nom}\n";
}

echo "\n=== فحص المواد والتخصصات ===\n";
$subjects = \App\Models\Subject::with('specialite')->get();
foreach ($subjects as $subject) {
    echo "المادة: {$subject->nom} (ID: {$subject->id})\n";
    echo "specialite_id: {$subject->specialite_id}\n";
    echo "التخصص: " . ($subject->specialite ? $subject->specialite->nom : 'لا يوجد') . "\n";
    echo "---\n";
}

echo "\n=== انتهى الفحص ===\n";
?>
