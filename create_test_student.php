<?php
// create_test_student.php
// إنشاء طالب تجريبي للاختبار

use App\Models\Student;
use App\Models\User;
use App\Models\Classe;
use Illuminate\Support\Facades\Hash;

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>إنشاء طالب تجريبي</h2>";

try {
    // البحث عن فصل موجود
    $classe = Classe::first();
    if (!$classe) {
        echo "<p style='color: red;'>لا يوجد فصول في النظام</p>";
        exit;
    }

    echo "<p>تم العثور على الفصل: {$classe->nom}</p>";

    // التحقق من وجود الطالب
    $existingStudent = Student::where('nni', '1234567890')->first();
    if ($existingStudent) {
        echo "<p style='color: orange;'>الطالب موجود بالفعل - ID: {$existingStudent->id}</p>";
        echo "<p>معلومات الطالب:</p>";
        echo "<ul>";
        echo "<li>الاسم: {$existingStudent->fullname}</li>";
        echo "<li>NNI: {$existingStudent->nni}</li>";
        echo "<li>الفصل: {$existingStudent->classe->nom}</li>";
        echo "<li>المستخدم: {$existingStudent->user->name}</li>";
        echo "</ul>";
    } else {
        // إنشاء مستخدم جديد
        $user = User::create([
            'name' => '1234567890',
            'email' => 'student_1234567890@test.com',
            'password' => Hash::make('1234567890'),
            'role_id' => 3
        ]);

        echo "<p>تم إنشاء المستخدم - ID: {$user->id}</p>";

        // إنشاء طالب جديد
        $student = Student::create([
            'nni' => '1234567890',
            'fullname' => 'أحمد محمد علي',
            'parent_name' => 'محمد علي حسن',
            'phone' => '77123456',
            'class_id' => $classe->id,
            'user_id' => $user->id
        ]);

        echo "<p style='color: green;'>تم إنشاء الطالب بنجاح - ID: {$student->id}</p>";
        echo "<p>معلومات الطالب:</p>";
        echo "<ul>";
        echo "<li>الاسم: {$student->fullname}</li>";
        echo "<li>NNI: {$student->nni}</li>";
        echo "<li>الفصل: {$student->classe->nom}</li>";
        echo "<li>المستخدم: {$student->user->name}</li>";
        echo "</ul>";
    }

    echo "<hr>";
    echo "<h3>اختبار تسجيل الدخول:</h3>";
    
    // اختبار تسجيل الدخول
    $testUser = User::where('name', '1234567890')->first();
    if ($testUser && Hash::check('1234567890', $testUser->password)) {
        echo "<p style='color: green;'>✅ اختبار كلمة المرور نجح</p>";
    } else {
        echo "<p style='color: red;'>❌ اختبار كلمة المرور فشل</p>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>خطأ: " . $e->getMessage() . "</p>";
}
?>