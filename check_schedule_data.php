<?php
// check_schedule_data.php
// التحقق من بيانات الجدول الزمني للصف L1 Info A

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Classe;
use App\Models\EmploiTemps;
use App\Models\Jour;
use App\Models\Horaire;
use App\Models\Student;

echo "<h2>🔍 فحص بيانات الجدول الزمني - L1 Info A</h2>";

// 1. البحث عن الصف
echo "<h3>1. معلومات الصف:</h3>";
$classe = Classe::where('nom', 'LIKE', '%L1 Info A%')
    ->orWhere('nom', 'LIKE', '%L1%')
    ->orWhere('nom', 'LIKE', '%Info A%')
    ->first();

if (!$classe) {
    $classe = Classe::where('nom', 'LIKE', '%Info%')->first();
}

if (!$classe) {
    echo "<p style='color: red;'>❌ لم يتم العثور على الصف L1 Info A</p>";
    echo "<h4>الصفوف المتاحة:</h4>";
    $allClasses = Classe::all();
    foreach ($allClasses as $c) {
        echo "<p>ID: {$c->id} - {$c->nom}</p>";
    }
    exit;
}

echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
echo "<p><strong>ID:</strong> {$classe->id}</p>";
echo "<p><strong>الاسم:</strong> {$classe->nom}</p>";
echo "<p><strong>المستوى:</strong> " . ($classe->niveau ?? 'غير محدد') . "</p>";
echo "<p><strong>التخصص:</strong> " . ($classe->specialite ?? 'غير محدد') . "</p>";
echo "</div>";

// 2. البحث عن يوم الاثنين
echo "<h3>2. معلومات يوم الاثنين:</h3>";
$jourLundi = Jour::where('libelle_ar', 'LIKE', '%الاثنين%')
    ->orWhere('libelle_fr', 'LIKE', '%Lundi%')
    ->first();

if ($jourLundi) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>ID:</strong> {$jourLundi->id}</p>";
    echo "<p><strong>الاسم بالعربي:</strong> {$jourLundi->libelle_ar}</p>";
    echo "<p><strong>الاسم بالفرنسي:</strong> {$jourLundi->libelle_fr}</p>";
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ لم يتم العثور على يوم الاثنين</p>";
}

// 3. البحث عن الوقت 8-9
echo "<h3>3. معلومات الوقت 8-9:</h3>";
$horaire8_9 = Horaire::where('heure_debut', 'LIKE', '%08:00%')
    ->orWhere('libelle_ar', 'LIKE', '%8%')
    ->orWhere('libelle_fr', 'LIKE', '%8%')
    ->first();

if (!$horaire8_9) {
    $horaire8_9 = Horaire::where('heure_debut', '>=', '08:00:00')
        ->where('heure_debut', '<=', '08:30:00')
        ->first();
}

if ($horaire8_9) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>ID:</strong> {$horaire8_9->id}</p>";
    echo "<p><strong>الاسم بالعربي:</strong> {$horaire8_9->libelle_ar}</p>";
    echo "<p><strong>الاسم بالفرنسي:</strong> {$horaire8_9->libelle_fr}</p>";
    echo "<p><strong>وقت البدء:</strong> {$horaire8_9->heure_debut}</p>";
    echo "<p><strong>وقت الانتهاء:</strong> {$horaire8_9->heure_fin}</p>";
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ لم يتم العثور على الوقت 8-9</p>";
    echo "<h4>الأوقات المتاحة:</h4>";
    $allHoraires = Horaire::orderBy('ordre')->get();
    foreach ($allHoraires as $h) {
        echo "<p>ID: {$h->id} - {$h->libelle_ar} ({$h->heure_debut} - {$h->heure_fin})</p>";
    }
}

// 4. البحث عن الحصة
echo "<h3>4. الحصة المحددة (الاثنين 8-9 - L1 Info A):</h3>";
if ($jourLundi && $horaire8_9) {
    $emploi = EmploiTemps::where('class_id', $classe->id)
        ->where('jour_id', $jourLundi->id)
        ->where('horaire_id', $horaire8_9->id)
        ->with(['matiere', 'enseignant', 'annee'])
        ->first();

    if ($emploi) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
        echo "<p style='color: green;'><strong>✅ تم العثور على الحصة!</strong></p>";
        echo "<p><strong>ID:</strong> {$emploi->id}</p>";
        echo "<p><strong>المادة:</strong> " . ($emploi->matiere->designation ?? 'غير محددة') . "</p>";
        echo "<p><strong>الأستاذ:</strong> " . ($emploi->enseignant->fullname ?? 'غير محدد') . "</p>";
        echo "<p><strong>السنة الدراسية:</strong> " . ($emploi->annee->designation ?? 'غير محددة') . "</p>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>❌ لم يتم العثور على الحصة في قاعدة البيانات!</p>";

        echo "<h4>جميع حصص هذا الصف:</h4>";
        $allEmplois = EmploiTemps::where('class_id', $classe->id)
            ->with(['matiere', 'enseignant', 'jour', 'horaire'])
            ->get();

        if ($allEmplois->count() > 0) {
            echo "<table style='width: 100%; border-collapse: collapse;'>";
            echo "<tr style='background: #f8f9fa;'>";
            echo "<th style='border: 1px solid #ddd; padding: 8px;'>اليوم</th>";
            echo "<th style='border: 1px solid #ddd; padding: 8px;'>الوقت</th>";
            echo "<th style='border: 1px solid #ddd; padding: 8px;'>المادة</th>";
            echo "<th style='border: 1px solid #ddd; padding: 8px;'>الأستاذ</th>";
            echo "</tr>";

            foreach ($allEmplois as $emp) {
                echo "<tr>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . ($emp->jour->libelle_ar ?? 'N/A') . "</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . ($emp->horaire->libelle_ar ?? 'N/A') . "</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . ($emp->matiere->designation ?? 'N/A') . "</td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'>" . ($emp->enseignant->fullname ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ لا توجد أي حصص مسجلة لهذا الصف!</p>";
        }
    }
}

// 5. اختبار API
echo "<hr>";
echo "<h3>5. اختبار API للطالب:</h3>";

// البحث عن طالب في هذا الصف
$student = Student::where('class_id', $classe->id)->first();

if ($student) {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>طالب للاختبار:</strong></p>";
    echo "<p><strong>الاسم:</strong> {$student->fullname}</p>";
    echo "<p><strong>NNI:</strong> {$student->nni}</p>";
    echo "<p><strong>الصف:</strong> {$classe->nom}</p>";
    echo "</div>";

    // محاكاة استجابة API
    echo "<h4>محاكاة استجابة API:</h4>";

    $horaires = Horaire::orderBy('ordre')->get();
    $jours = Jour::orderBy('ordre')->get();

    $scheduleMatrix = [];

    foreach ($horaires as $horaire) {
        $timeSlot = [
            'time_info' => [
                'id' => $horaire->id,
                'libelle_ar' => $horaire->libelle_ar,
                'libelle_fr' => $horaire->libelle_fr,
                'heure_debut' => $horaire->heure_debut,
                'heure_fin' => $horaire->heure_fin
            ],
            'classes' => []
        ];

        foreach ($jours as $jour) {
            $emploi = EmploiTemps::where('class_id', $classe->id)
                ->where('horaire_id', $horaire->id)
                ->where('jour_id', $jour->id)
                ->with(['matiere', 'enseignant'])
                ->first();

            $isLundi89 = ($jour->id == ($jourLundi->id ?? 0)) && ($horaire->id == ($horaire8_9->id ?? 0));

            $classData = [
                'day_info' => [
                    'id' => $jour->id,
                    'libelle_ar' => $jour->libelle_ar,
                    'libelle_fr' => $jour->libelle_fr
                ],
                'class_data' => [
                    'has_class' => $emploi ? true : false,
                    'subject' => $emploi ? ($emploi->matiere->designation ?? '') : '',
                    'teacher' => $emploi ? ($emploi->enseignant->fullname ?? '') : '',
                ]
            ];

            // تمييز الحصة المطلوبة
            if ($isLundi89) {
                $classData['is_target'] = true;
            }

            $timeSlot['classes'][] = $classData;
        }

        $scheduleMatrix[] = $timeSlot;
    }

    echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto;'>";
    echo json_encode([
        'student' => [
            'fullname' => $student->fullname,
            'nni' => $student->nni
        ],
        'class_info' => [
            'nom' => $classe->nom,
            'niveau' => $classe->niveau ?? '',
            'specialite' => $classe->specialite ?? ''
        ],
        'schedule_matrix' => $scheduleMatrix
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "</pre>";

} else {
    echo "<p style='color: orange;'>⚠️ لا يوجد طلاب في هذا الصف</p>";
}

// 6. جميع الأيام والأوقات
echo "<hr>";
echo "<h3>6. جميع الأيام المتاحة:</h3>";
$allJours = Jour::orderBy('ordre')->get();
echo "<ul>";
foreach ($allJours as $j) {
    echo "<li>ID: {$j->id} - {$j->libelle_ar} ({$j->libelle_fr}) - الترتيب: {$j->ordre}</li>";
}
echo "</ul>";

echo "<h3>7. جميع الأوقات المتاحة:</h3>";
$allHoraires = Horaire::orderBy('ordre')->get();
echo "<ul>";
foreach ($allHoraires as $h) {
    echo "<li>ID: {$h->id} - {$h->libelle_ar} ({$h->libelle_fr}) - {$h->heure_debut} إلى {$h->heure_fin} - الترتيب: {$h->ordre}</li>";
}
echo "</ul>";
?>
