# دليل الإعداد السريع - Quick Setup Guide

## Student Management API - نظام إدارة الطلاب

### التثبيت والإعداد - Installation & Setup

#### 1. متطلبات النظام - System Requirements
```
- Laravel 10+
- PHP 8.1+
- MySQL/MariaDB
- Composer
- Node.js & NPM (للواجهة الأمامية)
```

#### 2. إعداد قاعدة البيانات - Database Setup
```sql
-- تشغيل الـ migrations المطلوبة
php artisan migrate

-- إضافة بيانات تجريبية (اختياري)
php artisan db:seed
```

#### 3. إعداد Sanctum للـ API
```bash
# تثبيت Sanctum
composer require laravel/sanctum

# نشر إعدادات Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# إضافة الجداول المطلوبة
php artisan migrate
```

#### 4. إعداد Storage للصور
```bash
# إنشاء رابط للـ Storage
php artisan storage:link
```

### الاستخدام السريع - Quick Usage

#### 1. تسجيل طالب جديد (عبر لوحة الإدارة)
```
1. اذهب إلى: /admin/students
2. انقر على "إضافة طالب جديد"
3. املأ البيانات المطلوبة
4. سيتم إنشاء حساب مستخدم تلقائياً
```

#### 2. اختبار API

##### تسجيل الدخول:
```bash
curl -X POST http://172.20.10.4:8000/api/student/login \
  -H "Content-Type: application/json" \
  -d '{
    "nni": "1234567890",
    "password": "1234567890"
  }'
```

##### جلب الملف الشخصي:
```bash
curl -X GET http://172.20.10.4:8000/api/student/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

##### جلب الجدول الزمني:
```bash
curl -X GET http://172.20.10.4:8000/api/student/schedule \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

##### جلب بيانات الجدول المنسقة:
```bash
curl -X GET http://172.20.10.4:8000/api/student/schedule/data \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

##### تحميل الجدول كملف PDF:
```bash
curl -X GET http://172.20.10.4:8000/api/student/schedule/pdf \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  --output "student_schedule.pdf"
```

### إعداد التطبيق المحمول - Mobile App Setup

#### 1. Base URL
```
http://172.20.10.4:8000/api/student/
```

#### 2. Headers المطلوبة
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer TOKEN_HERE"
}
```

#### 3. تدفق المصادقة - Authentication Flow
```
1. POST /login → احصل على token
2. احفظ الـ token في التطبيق
3. استخدم الـ token في كل الطلبات
4. POST /logout → عند تسجيل الخروج
```

### أكواد الأخطاء الشائعة - Common Error Codes

| كود | الرسالة | السبب |
|-----|---------|-------|
| 401 | بيانات الدخول غير صحيحة | NNI أو كلمة مرور خاطئة |
| 401 | Unauthenticated | Token غير صحيح أو منتهي الصلاحية |
| 404 | Student not found | الطالب غير موجود |
| 422 | Validation Error | بيانات مُدخلة خاطئة |
| 500 | Server Error | خطأ في الخادم |

### بيانات الاختبار - Test Data

```json
{
  "nni": "1234567890",
  "password": "1234567890",
  "fullname": "أحمد محمد علي",
  "parent_name": "محمد علي حسن",
  "phone": "77123456"
}
```

### Postman Collection

استورد الملف: `Student_API_Postman_Collection.json`
- يحتوي على جميع الـ endpoints
- اختبارات تلقائية للـ responses
- إدارة تلقائية للـ tokens

### أمثلة الكود الجاهزة - Ready Code Examples

#### Flutter Service Class:
```dart
class StudentApiService {
  static const String baseUrl = 'http://172.20.10.4:8000/api/student';
  String? _token;
  
  Future<LoginResponse> login(String nni, String password) async {
    // Implementation in MOBILE_CODE_EXAMPLES.md
  }
  
  Future<StudentProfile> getProfile() async {
    // Implementation in MOBILE_CODE_EXAMPLES.md
  }
  
  Future<WeeklySchedule> getSchedule() async {
    // Implementation in MOBILE_CODE_EXAMPLES.md
  }
}
```

#### React Native Service Class:
```javascript
class StudentApiService {
  constructor() {
    this.baseUrl = 'http://172.20.10.4:8000/api/student';
    this.token = null;
  }
  
  async login(nni, password) {
    // Implementation in MOBILE_CODE_EXAMPLES.md
  }
  
  async getProfile() {
    // Implementation in MOBILE_CODE_EXAMPLES.md
  }
  
  async getSchedule() {
    // Implementation in MOBILE_CODE_EXAMPLES.md
  }
}
```

### استكشاف الأخطاء - Troubleshooting

#### مشاكل شائعة:
1. **CORS Issues**: تأكد من إعداد CORS في `config/cors.php`
2. **Token Expiration**: تحقق من إعدادات Sanctum في `config/sanctum.php`
3. **Image Upload**: تأكد من أن `storage/app/public` قابل للكتابة
4. **Database Connection**: تحقق من إعدادات `.env`

#### Debug Mode:
```bash
# لتفعيل وضع التطوير
APP_DEBUG=true

# لعرض تفاصيل الأخطاء
LOG_LEVEL=debug
```

### الأمان - Security

#### نصائح هامة:
- استخدم HTTPS في الإنتاج
- قم بتحديث Sanctum بانتظام
- اعتمد على token expiration
- لا تخزن كلمات المرور في plain text
- استخدم rate limiting للـ API

### الدعم الفني - Support

للمساعدة التقنية أو الأسئلة:
- راجع الملفات: `API_DOCUMENTATION_FOR_MOBILE_DEVELOPER.md`
- راجع أمثلة الكود: `MOBILE_CODE_EXAMPLES.md`
- اختبر باستخدام: `Student_API_Postman_Collection.json`

---

### ملاحظات مهمة - Important Notes

1. **كلمة المرور الافتراضية**: نفس رقم الهوية الوطنية (NNI)
2. **الصور**: اختيارية ولها رابط افتراضي
3. **الجدول الزمني**: يعتمد على الصف المسجل فيه الطالب
4. **المصادقة**: مطلوبة لجميع العمليات عدا تسجيل الدخول

### التحديثات المستقبلية - Future Updates

المخطط إضافة:
- إشعارات Push
- تحديث كلمة المرور
- تحديث الصورة الشخصية
- عرض الدرجات والنتائج
- تقويم الأحداث المدرسية
