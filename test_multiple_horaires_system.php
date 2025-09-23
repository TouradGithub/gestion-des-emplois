<?php
/**
 * اختبار نظام الحصص الزمنية المتعددة
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار نظام الحصص الزمنية المتعددة ===\n\n";

// فحص الحصص الزمنية المتاحة
echo "1. الحصص الزمنية المتاحة:\n";
$horaires = \App\Models\Horaire::all();
foreach ($horaires as $horaire) {
    echo "  - ID: {$horaire->id} - {$horaire->libelle_fr}\n";
}

echo "\n2. فحص EmploiHoraire relationships:\n";
$emploiHoraires = \App\Models\EmploiHoraire::with(['emploiTemps', 'horaire'])->get();
echo "إجمالي سجلات EmploiHoraire: " . $emploiHoraires->count() . "\n";

foreach ($emploiHoraires as $eh) {
    echo "  - EmploiTemps ID: {$eh->emploi_temps_id}, Horaire: " . ($eh->horaire->libelle_fr ?? 'N/A') . "\n";
}

echo "\n3. فحص علاقة EmploiTemps مع horairess:\n";
$emplois = \App\Models\EmploiTemps::with(['horairess', 'classe', 'subject', 'teacher'])->get();
echo "إجمالي الحصص: " . $emplois->count() . "\n";

foreach ($emplois as $emploi) {
    echo "Emploi ID: {$emploi->id}\n";
    echo "  - الفصل: " . ($emploi->classe->nom ?? 'N/A') . "\n";
    echo "  - المادة: " . ($emploi->subject->name ?? 'N/A') . "\n";
    echo "  - الأستاذ: " . ($emploi->teacher->name ?? 'N/A') . "\n";
    echo "  - الحصص الزمنية (" . $emploi->horairess->count() . "):\n";

    foreach ($emploi->horairess as $horaire) {
        echo "    * {$horaire->libelle_fr}\n";
    }
    echo "---\n";
}

echo "\n4. إنشاء حصة تجريبية مع حصص زمنية متعددة:\n";

// إنشاء حصة تجريبية
try {
    $emploi = \App\Models\EmploiTemps::create([
        'class_id' => 1,
        'subject_id' => 1,
        'teacher_id' => 1,
        'trimester_id' => 1,
        'annee_id' => 6,
        'jour_id' => 1,
    ]);

    echo "✅ تم إنشاء الحصة بنجاح - ID: {$emploi->id}\n";

    // إضافة حصص زمنية متعددة
    $horaire_ids = [1, 2]; // مثال: الحصة الأولى والثانية

    foreach ($horaire_ids as $horaire_id) {
        if (\App\Models\Horaire::find($horaire_id)) {
            \App\Models\EmploiHoraire::create([
                'emploi_temps_id' => $emploi->id,
                'horaire_id' => $horaire_id,
            ]);
            echo "  ✅ تم ربط الحصة الزمنية ID: $horaire_id\n";
        }
    }

    // فحص النتيجة
    $emploi->load('horairess');
    echo "  📊 إجمالي الحصص الزمنية المرتبطة: " . $emploi->horairess->count() . "\n";

    foreach ($emploi->horairess as $horaire) {
        echo "    - {$horaire->libelle_fr}\n";
    }

} catch (\Exception $e) {
    echo "❌ خطأ في إنشاء الحصة: " . $e->getMessage() . "\n";
}

echo "\n=== انتهى الاختبار ===\n";
?>
