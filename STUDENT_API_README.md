# Student API Documentation

## نظام إدارة الطلاب - واجهة برمجة التطبيقات

تم إنشاء نظام متكامل لإدارة الطلاب مع واجهة برمجة تطبيقات للتطبيقات الجوال.

## الميزات المُنجَزة:

### ✅ إدارة الطلاب
- إنشاء ملف تعريف الطالب مع المعلومات التالية:
  - NNI (رقم التعريف الوطني - فريد)
  - الاسم الكامل
  - اسم ولي الأمر
  - رقم الهاتف
  - الصورة (اختياري)
  - الفصل الدراسي

### ✅ إنشاء الحسابات التلقائي
- عند تسجيل طالب جديد، يتم إنشاء حساب مستخدم تلقائياً
- الدور: 3 (طالب)
- اسم المستخدم: NNI
- كلمة المرور الافتراضية: NNI

### ✅ واجهة إدارة الطلاب
- صفحة قائمة الطلاب مع إمكانيات البحث والتصفية
- إضافة طلاب جدد
- تعديل بيانات الطلاب
- حذف الطلاب
- عرض تفاصيل الطالب
- رفع وإدارة صور الطلاب

### ✅ واجهة برمجة التطبيقات للتطبيق الجوال

## API Endpoints:

### 1. تسجيل الدخول
```
POST /api/student/login
Content-Type: application/json

{
    "nni": "رقم التعريف الوطني",
    "password": "كلمة المرور"
}
```

**الاستجابة عند النجاح:**
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "student": {
            "id": 1,
            "nni": "1234567890",
            "fullname": "أحمد محمد",
            "parent_name": "محمد علي",
            "phone": "77123456",
            "image": "http://localhost/storage/students/image.jpg",
            "class": {
                "id": 1,
                "nom": "الصف الأول أ",
                "niveau": "المستوى الأول",
                "specialite": "علوم",
                "annee": "2023-2024"
            }
        },
        "token": "API_TOKEN_HERE"
    }
}
```

### 2. عرض الملف الشخصي
```
GET /api/student/profile
Authorization: Bearer {token}
```

### 3. عرض الجدول الزمني
```
GET /api/student/schedule
Authorization: Bearer {token}
```

**الاستجابة:**
```json
{
    "success": true,
    "message": "تم جلب الجدول الزمني بنجاح",
    "data": {
        "class_info": {
            "id": 1,
            "nom": "الصف الأول أ"
        },
        "schedule": {
            "الأحد": [
                {
                    "id": 1,
                    "subject": {
                        "id": 1,
                        "name": "الرياضيات"
                    },
                    "teacher": {
                        "id": 1,
                        "name": "أحمد محمد"
                    },
                    "trimester": {
                        "id": 1,
                        "name": "الفصل الأول"
                    },
                    "horaires": [
                        {
                            "id": 1,
                            "start_time": "08:00",
                            "end_time": "09:00",
                            "libelle_fr": "8h00-9h00",
                            "libelle_ar": "8:00-9:00"
                        }
                    ]
                }
            ]
        }
    }
}
```

### 4. تحميل الجدول الزمني كملف PDF
```
GET /api/student/schedule/pdf
Authorization: Bearer {token}
```

**الاستجابة:**
- ملف PDF يحتوي على الجدول الزمني الكامل للطالب
- منسق للطباعة مع معلومات الطالب والصف
- اسم الملف: `student_schedule_{NNI}_{date}.pdf`

### 5. تسجيل الخروج
```
POST /api/student/logout
Authorization: Bearer {token}
```

## روابط الإدارة:

### لوحة التحكم الإدارية:
- **قائمة الطلاب**: `/web/students`
- **إضافة طالب جديد**: `/web/students/create`
- **تعديل طالب**: `/web/students/{id}/edit`
- **عرض تفاصيل طالب**: `/web/students/{id}`

## قاعدة البيانات:

### جدول الطلاب (students):
- id (Primary Key)
- nni (فريد)
- fullname
- parent_name
- phone
- image (nullable)
- class_id (Foreign Key إلى classes)
- user_id (Foreign Key إلى users)
- created_at
- updated_at

### العلاقات:
- **Student -> Classe**: belongsTo
- **Student -> User**: belongsTo
- **Student -> EmploiTemps**: hasMany (من خلال class_id)
- **Classe -> Students**: hasMany

## الأمان:
- استخدام Laravel Sanctum للمصادقة
- تشفير كلمات المرور
- التحقق من صحة البيانات
- حماية ضد SQL Injection
- رفع الصور بشكل آمن

## ملاحظات مهمة:
1. يجب تفعيل Laravel Sanctum في المشروع
2. يجب إنشاء دور "Student" برقم 3 في جدول roles
3. يجب تكوين Storage للسماح برفع الصور
4. جميع النصوص مترجمة باللغتين العربية والفرنسية

## اختبار API:

يمكن اختبار الـ API باستخدام Postman أو أي أداة أخرى:

1. تسجيل دخول طالب والحصول على token
2. استخدام token في Header للوصول للبيانات المحمية
3. عرض الجدول الزمني للطالب

## الخطوات التالية الموصى بها:
1. إضافة إشعارات push للتطبيق الجوال
2. إضافة نظام الحضور والغياب للطلاب
3. إضافة نظام الدرجات والتقييم
4. إضافة إمكانية تحديث كلمة المرور للطالب
5. إضافة نظام الواجبات المنزلية
