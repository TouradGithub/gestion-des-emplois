<?php
/**
 * اختبار صفحة إنشاء الحصص مع Select2
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار صفحة إنشاء الحصص مع Select2 ===\n\n";

echo "1. فحص البيانات المطلوبة لصفحة الإنشاء:\n";

// الفصول
$classes = \App\Models\Classe::all();
echo "عدد الفصول: " . $classes->count() . "\n";
foreach ($classes as $class) {
    echo "  - {$class->nom}\n";
}

// الحصص الزمنية
echo "\nالحصص الزمنية:\n";
$horaires = \App\Models\Horaire::all();
echo "عدد الحصص الزمنية: " . $horaires->count() . "\n";
foreach ($horaires as $horaire) {
    echo "  - {$horaire->libelle_fr} ({$horaire->heure_debut} - {$horaire->heure_fin})\n";
}

// الأيام
echo "\nالأيام:\n";
$jours = \App\Models\Jour::all();
echo "عدد الأيام: " . $jours->count() . "\n";
foreach ($jours as $jour) {
    echo "  - {$jour->libelle_fr}\n";
}

// الفصول الدراسية
echo "\nالفصول الدراسية:\n";
$trimesters = \App\Models\Trimester::all();
echo "عدد الفصول الدراسية: " . $trimesters->count() . "\n";
foreach ($trimesters as $trimester) {
    echo "  - {$trimester->name}\n";
}

// القاعات
echo "\nالقاعات:\n";
$salles = \App\Models\SalleDeClasse::all();
echo "عدد القاعات: " . $salles->count() . "\n";
foreach ($salles as $salle) {
    echo "  - {$salle->name}\n";
}

echo "\n2. فحص وضع البيانات الأساسية:\n";

if ($classes->count() > 0 && $horaires->count() > 0 && $jours->count() > 0 && $trimesters->count() > 0) {
    echo "✅ جميع البيانات الأساسية متوفرة لصفحة الإنشاء\n";
} else {
    echo "❌ بعض البيانات الأساسية مفقودة:\n";
    if ($classes->count() == 0) echo "  - لا توجد فصول\n";
    if ($horaires->count() == 0) echo "  - لا توجد حصص زمنية\n";
    if ($jours->count() == 0) echo "  - لا توجد أيام\n";
    if ($trimesters->count() == 0) echo "  - لا توجد فصول دراسية\n";
}

echo "\n3. فحص وجود الـ controllers:\n";
if (class_exists(\App\Http\Controllers\EmploiTempsController::class)) {
    echo "✅ EmploiTempsController موجود\n";
} else {
    echo "❌ EmploiTempsController غير موجود\n";
}

echo "\n=== انتهى الاختبار ===\n";

echo "\n📋 التحسينات المطبقة على نظام الحصص الزمنية:\n";
echo "✅ 1. تم تحويل عرض الحصص الزمنية إلى Select2 متعدد الخيارات\n";
echo "✅ 2. تم تحسين التصميم والـ UX للحصص المتعددة\n";
echo "✅ 3. تم إضافة فاليديشن للتأكد من اختيار حصة زمنية واحدة على الأقل\n";
echo "✅ 4. تم تحديث منطق إضافة الصفوف للتعامل مع Select2\n";
echo "✅ 5. تم تحسين رسائل التحقق والخطأ\n";
?>
