<?php
/**
 * اختبار نظام الحصص المتعددة
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار نظام الحصص المتعددة ===\n\n";

// فحص الحصص الموجودة
echo "1. فحص الحصص الموجودة:\n";
$emplois = \App\Models\EmploiTemps::with(['classe', 'teacher', 'subject', 'jour', 'horairess'])->get();
echo "إجمالي الحصص: " . $emplois->count() . "\n\n";

foreach ($emplois as $emploi) {
    echo "الحصة رقم {$emploi->id}:\n";
    echo "  - الفصل: " . ($emploi->classe->nom ?? 'غير محدد') . "\n";
    echo "  - الأستاذ: " . ($emploi->teacher->name ?? 'غير محدد') . "\n";
    echo "  - المادة: " . ($emploi->subject->name ?? 'غير محدد') . "\n";
    echo "  - اليوم: " . ($emploi->jour->libelle_fr ?? 'غير محدد') . "\n";
    echo "  - الحصص الزمنية: ";

    if ($emploi->horairess->count() > 0) {
        foreach ($emploi->horairess as $horaire) {
            echo $horaire->libelle_fr . " ";
        }
    } else {
        echo "لا توجد حصص زمنية";
    }
    echo "\n---\n";
}

// فحص جدول emploi_horaire
echo "\n2. فحص جدول emploi_horaire:\n";
$emploiHoraires = \App\Models\EmploiHoraire::with(['emploi', 'horaire'])->get();
echo "إجمالي السجلات: " . $emploiHoraires->count() . "\n\n";

foreach ($emploiHoraires as $eh) {
    echo "السجل رقم {$eh->id}:\n";
    echo "  - emploi_temps_id: {$eh->emploi_temps_id}\n";
    echo "  - horaire_id: {$eh->horaire_id}\n";
    echo "  - الحصة الزمنية: " . ($eh->horaire->libelle_fr ?? 'غير محدد') . "\n";
    echo "---\n";
}

// فحص الحصص الزمنية المتاحة
echo "\n3. الحصص الزمنية المتاحة:\n";
$horaires = \App\Models\Horaire::all();
foreach ($horaires as $horaire) {
    echo "- {$horaire->libelle_fr} (ID: {$horaire->id})\n";
}

echo "\n=== انتهى الاختبار ===\n";
?>
