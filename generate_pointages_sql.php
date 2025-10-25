<?php

// إنشاء بيانات حضور تجريبية مباشرة
echo "=== إنشاء بيانات حضور تجريبية ===\n\n";

// البيانات التي سنضيفها يدوياً
$pointages_data = [
    [
        'emploi_temps_id' => 1,
        'teacher_id' => 1, // أحمد علي
        'date_pointage' => '2024-10-21', // اليوم - 2
        'statut' => 'present',
        'heure_arrivee' => '08:15',
        'heure_depart' => '12:30',
        'remarques' => 'حضر في الوقت المحدد',
        'created_by' => 1,
    ],
    [
        'emploi_temps_id' => 1,
        'teacher_id' => 1, // أحمد علي
        'date_pointage' => '2024-10-22', // اليوم - 1
        'statut' => 'absent',
        'heure_arrivee' => null,
        'heure_depart' => null,
        'remarques' => 'غياب بعذر طبي',
        'created_by' => 1,
    ],
    [
        'emploi_temps_id' => 1,
        'teacher_id' => 1, // أحمد علي
        'date_pointage' => '2024-10-23', // اليوم
        'statut' => 'present',
        'heure_arrivee' => '08:00',
        'heure_depart' => '12:45',
        'remarques' => 'حضور متميز',
        'created_by' => 1,
    ],
    // سعاد محمد
    [
        'emploi_temps_id' => 1,
        'teacher_id' => 2, // سعاد محمد
        'date_pointage' => '2024-10-21',
        'statut' => 'present',
        'heure_arrivee' => '08:30',
        'heure_depart' => '12:15',
        'remarques' => 'تأخر 15 دقيقة',
        'created_by' => 1,
    ],
    [
        'emploi_temps_id' => 1,
        'teacher_id' => 2, // سعاد محمد
        'date_pointage' => '2024-10-22',
        'statut' => 'present',
        'heure_arrivee' => '08:10',
        'heure_depart' => '12:30',
        'remarques' => 'حضر في الوقت المناسب',
        'created_by' => 1,
    ],
    // يوسف عمر
    [
        'emploi_temps_id' => 1,
        'teacher_id' => 3, // يوسف عمر
        'date_pointage' => '2024-10-21',
        'statut' => 'absent',
        'heure_arrivee' => null,
        'heure_depart' => null,
        'remarques' => 'مؤتمر علمي',
        'created_by' => 1,
    ],
    [
        'emploi_temps_id' => 1,
        'teacher_id' => 3, // يوسف عمر
        'date_pointage' => '2024-10-23',
        'statut' => 'present',
        'heure_arrivee' => '07:55',
        'heure_depart' => '12:35',
        'remarques' => 'حضر مبكراً',
        'created_by' => 1,
    ],
];

echo "البيانات التي سيتم إضافتها:\n\n";

foreach ($pointages_data as $index => $data) {
    $teacher_names = [1 => 'أحمد علي', 2 => 'سعاد محمد', 3 => 'يوسف عمر'];
    $teacher_name = $teacher_names[$data['teacher_id']] ?? 'غير معروف';
    
    echo ($index + 1) . ". الأستاذ: {$teacher_name}\n";
    echo "   التاريخ: {$data['date_pointage']}\n";
    echo "   الحالة: " . ($data['statut'] === 'present' ? 'حاضر' : 'غائب') . "\n";
    if ($data['statut'] === 'present') {
        echo "   وقت الوصول: {$data['heure_arrivee']}\n";
        echo "   وقت المغادرة: {$data['heure_depart']}\n";
    }
    echo "   الملاحظات: {$data['remarques']}\n\n";
}

// SQL للإدراج المباشر
echo "استعلامات SQL للإدراج:\n\n";

foreach ($pointages_data as $data) {
    $sql = "INSERT INTO pointages (emploi_temps_id, teacher_id, date_pointage, statut, heure_arrivee, heure_depart, remarques, created_by, created_at, updated_at) VALUES (";
    $sql .= "{$data['emploi_temps_id']}, ";
    $sql .= "{$data['teacher_id']}, ";
    $sql .= "'{$data['date_pointage']}', ";
    $sql .= "'{$data['statut']}', ";
    $sql .= ($data['heure_arrivee'] ? "'{$data['heure_arrivee']}'" : "NULL") . ", ";
    $sql .= ($data['heure_depart'] ? "'{$data['heure_depart']}'" : "NULL") . ", ";
    $sql .= "'{$data['remarques']}', ";
    $sql .= "{$data['created_by']}, ";
    $sql .= "NOW(), NOW());";
    
    echo $sql . "\n";
}

echo "\n=== انتهى ===\n";