<?php

require_once 'vendor/autoload.php';

// تحميل إعدادات Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Teacher;
use App\Models\EmploiTemps;
use App\Models\Classe;
use App\Models\Subject;

echo "=== إضافة بيانات تجريبية للأساتذة والجداول ===\n\n";

// إضافة أساتذة جدد
$teachers = [
    ['name' => 'فاطمة الزهراء', 'email' => 'fatima@university.edu'],
    ['name' => 'محمد الأمين', 'email' => 'mohamed@university.edu'],
    ['name' => 'عائشة حسن', 'email' => 'aicha@university.edu'],
    ['name' => 'خالد عبدالله', 'email' => 'khaled@university.edu'],
    ['name' => 'مريم أحمد', 'email' => 'mariam@university.edu'],
];

$teachersAdded = 0;
foreach ($teachers as $teacherData) {
    $teacher = Teacher::firstOrCreate(
        ['email' => $teacherData['email']],
        $teacherData
    );
    
    if ($teacher->wasRecentlyCreated) {
        echo "✓ تم إضافة الأستاذ: {$teacherData['name']}\n";
        $teachersAdded++;
    }
}

echo "\nإجمالي الأساتذة المضافين: $teachersAdded\n";
echo "إجمالي الأساتذة في النظام: " . Teacher::count() . "\n\n";

// التحقق من وجود الفصول والمواد
$classes = Classe::count();
$subjects = Subject::count();

echo "الفصول في النظام: $classes\n";
echo "المواد في النظام: $subjects\n\n";

// إذا كان هناك أقل من 5 جداول دراسية، أضف المزيد
$currentEmplois = EmploiTemps::count();
echo "الجداول الدراسية الحالية: $currentEmplois\n";

if ($currentEmplois < 5) {
    echo "إضافة المزيد من الجداول الدراسية...\n";
    
    $allTeachers = Teacher::all();
    $allClasses = Classe::all();
    $allSubjects = Subject::all();
    
    if ($allTeachers->isNotEmpty() && $allClasses->isNotEmpty() && $allSubjects->isNotEmpty()) {
        $emploisToAdd = 5 - $currentEmplois;
        $emploisAdded = 0;
        
        for ($i = 0; $i < $emploisToAdd; $i++) {
            $randomTeacher = $allTeachers->random();
            $randomClass = $allClasses->random();
            $randomSubject = $allSubjects->random();
            
            // تحقق من عدم وجود جدول مماثل
            $exists = EmploiTemps::where('teacher_id', $randomTeacher->id)
                ->where('classe_id', $randomClass->id)
                ->where('subject_id', $randomSubject->id)
                ->exists();
                
            if (!$exists) {
                EmploiTemps::create([
                    'teacher_id' => $randomTeacher->id,
                    'classe_id' => $randomClass->id,
                    'subject_id' => $randomSubject->id,
                    'jour_id' => rand(1, 6), // من الاثنين إلى السبت
                    'semestre' => 'S1',
                ]);
                
                echo "✓ جدول جديد: {$randomTeacher->name} - {$randomClass->nom} - {$randomSubject->name}\n";
                $emploisAdded++;
            }
        }
        
        echo "\nتم إضافة $emploisAdded جدول دراسي جديد\n";
    }
}

$totalEmplois = EmploiTemps::count();
echo "\nإجمالي الجداول الدراسية الآن: $totalEmplois\n\n";

echo "=== البيانات جاهزة لإنشاء سجلات الحضور ===\n";