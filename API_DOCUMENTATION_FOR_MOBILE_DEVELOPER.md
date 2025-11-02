# دليل API لتطبيق إدارة الطلاب الجوال
# Student Management Mobile App API Guide

## معلومات عامة | General Information

### Base URL
```
Production: https://your-domain.com/api
Development: http://172.20.10.4:8000/api
```

### Authentication
يستخدم النظام **Laravel Sanctum** للمصادقة. يجب إرسال token في Header مع كل طلب محمي.

```
Authorization: Bearer YOUR_TOKEN_HERE
```

### Content-Type
جميع الطلبات يجب أن تحتوي على:
```
Content-Type: application/json
```

---

## 📱 API Endpoints

### 1. تسجيل الدخول | Login

**Endpoint:** `POST /api/student/login`

**الغرض:** تسجيل دخول الطالب للتطبيق والحصول على access token

**البيانات المطلوبة:**
```json
{
    "nni": "1234567890",     // رقم التعريف الوطني (مطلوب)
    "password": "1234567890" // كلمة المرور (افتراضياً نفس NNI)
}
```

**مثال على الطلب | Request Example:**
```bash
curl -X POST "http://172.20.10.4:8000/api/student/login" \
-H "Content-Type: application/json" \
-d '{
    "nni": "1234567890",
    "password": "1234567890"
}'
```

**الاستجابة عند النجاح | Success Response:**
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "student": {
            "id": 1,
            "nni": "1234567890",
            "fullname": "أحمد محمد علي",
            "parent_name": "محمد علي حسن",
            "phone": "77123456",
            "image": "http://localhost:8000/storage/students/image.jpg",
            "class": {
                "id": 1,
                "nom": "الصف الأول أ",
                "niveau": "المستوى الأول",
                "specialite": "علوم طبيعية",
                "annee": "2024-2025"
            }
        },
        "token": "1|abc123def456ghi789..."
    }
}
```

**الاستجابة عند الخطأ | Error Response:**
```json
{
    "success": false,
    "message": "بيانات الدخول غير صحيحة"
}
```

**رموز الحالة | Status Codes:**
- `200`: تم تسجيل الدخول بنجاح
- `401`: بيانات دخول خاطئة
- `404`: الطالب غير موجود
- `422`: بيانات غير صالحة

---

### 2. عرض الملف الشخصي | Get Profile

**Endpoint:** `GET /api/student/profile`

**الغرض:** جلب معلومات الطالب الشخصية

**Authentication:** مطلوب Bearer Token

**مثال على الطلب | Request Example:**
```bash
curl -X GET "http://172.20.10.4:8000/api/student/profile" \
-H "Authorization: Bearer 1|abc123def456ghi789..." \
-H "Content-Type: application/json"
```

**الاستجابة | Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nni": "1234567890",
        "fullname": "أحمد محمد علي",
        "parent_name": "محمد علي حسن",
        "phone": "77123456",
        "image": "http://localhost:8000/storage/students/image.jpg",
        "class": {
            "id": 1,
            "nom": "الصف الأول أ",
            "niveau": "المستوى الأول",
            "specialite": "علوم طبيعية",
            "annee": "2024-2025"
        }
    }
}
```

---

### 3. عرض الجدول الزمني | Get Schedule

**Endpoint:** `GET /api/student/schedule`

**الغرض:** جلب الجدول الزمني الأسبوعي للطالب

**Authentication:** مطلوب Bearer Token

**مثال على الطلب | Request Example:**
```bash
curl -X GET "http://172.20.10.4:8000/api/student/schedule" \
-H "Authorization: Bearer 1|abc123def456ghi789..." \
-H "Content-Type: application/json"
```

**الاستجابة | Response:**
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
                        "name": "أحمد محمد الأستاذ"
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
                    ],
                    "created_at": "2024-11-01T10:00:00.000000Z",
                    "updated_at": "2024-11-01T10:00:00.000000Z"
                }
            ],
            "الاثنين": [
                {
                    "id": 2,
                    "subject": {
                        "id": 2,
                        "name": "اللغة العربية"
                    },
                    "teacher": {
                        "id": 2,
                        "name": "فاطمة أحمد"
                    },
                    "trimester": {
                        "id": 1,
                        "name": "الفصل الأول"
                    },
                    "horaires": [
                        {
                            "id": 2,
                            "start_time": "09:00",
                            "end_time": "10:00",
                            "libelle_fr": "9h00-10h00",
                            "libelle_ar": "9:00-10:00"
                        }
                    ]
                }
            ]
        }
    }
}
```

---

### 4. Schedule Data (Formatted for Calendar Display)
**Endpoint:** `GET /api/student/schedule/data`
**Headers:** 
- Authorization: Bearer {token}
- Accept: application/json

**Response:**
```json
{
  "success": true,
  "data": {
    "student": {
      "fullname": "محمد أحمد علي",
      "nni": "1234567890",
      "image": "http://172.20.10.4:8000/storage/students/image.jpg"
    },
    "class_info": {
      "nom": "الصف الأول الثانوي",
      "niveau": "الأول الثانوي",
      "specialite": "علوم طبيعية"
    },
    "horaires": [
      {
        "id": 1,
        "libelle_ar": "الحصة الأولى",
        "libelle_fr": "1ère période",
        "heure_debut": "08:00:00",
        "heure_fin": "08:50:00"
      }
    ],
    "jours": [
      {
        "id": 1,
        "libelle_ar": "السبت",
        "libelle_fr": "Samedi"
      }
    ],
    "schedule_matrix": [
      {
        "time_info": {
          "id": 1,
          "libelle_ar": "الحصة الأولى",
          "libelle_fr": "1ère période"
        },
        "classes": [
          {
            "day_info": {
              "id": 1,
              "libelle_ar": "السبت",
              "libelle_fr": "Samedi"
            },
            "class_data": {
              "has_class": true,
              "subject": "الرياضيات",
              "teacher": "أ. عبدالله محمد"
            }
          }
        ]
      }
    ]
  }
}
```

**Key Features:**
- **schedule_matrix**: Ready-to-use calendar format
- **student**: Complete student information with image
- **class_info**: Class details including specialty
- **horaires**: All available time slots
- **jours**: All days of the week

**cURL Example:**
```bash
curl -X GET "http://172.20.10.4:8000/api/student/schedule/data" \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "Accept: application/json"
```

**JavaScript Example:**
```javascript
fetch('http://172.20.10.4:8000/api/student/schedule/data', {
    headers: {
        'Authorization': 'Bearer ' + token,
        'Accept': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    const scheduleMatrix = data.data.schedule_matrix;
    // Use schedule_matrix to build calendar UI
    scheduleMatrix.forEach(timeSlot => {
        timeSlot.classes.forEach(dayClass => {
            if (dayClass.class_data.has_class) {
                console.log(`${timeSlot.time_info.libelle_ar} - ${dayClass.day_info.libelle_ar}: ${dayClass.class_data.subject}`);
            }
        });
    });
});
```

### 5. Schedule PDF Download
**Endpoint:** `GET /api/student/schedule/pdf`
**Headers:** 
- Authorization: Bearer {token}
**Response:** PDF file download

**cURL Example:**
```bash
curl -X GET "http://172.20.10.4:8000/api/student/schedule/pdf" \
     -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

### 5. تحميل الجدول كملف PDF | Download Schedule as PDF

**Endpoint:** `GET /api/student/schedule/pdf`

**الغرض:** تحميل الجدول الزمني للطالب كملف PDF جاهز للطباعة

**Authentication:** مطلوب Bearer Token

**نوع الاستجابة:** ملف PDF (binary)

**مثال على الطلب | Request Example:**
```bash
curl -X GET "http://172.20.10.4:8000/api/student/schedule/pdf" \
-H "Authorization: Bearer 1|abc123def456ghi789..." \
--output "student_schedule.pdf"
```

**الاستجابة | Response:**
- **نوع المحتوى:** `application/pdf`
- **اسم الملف:** `student_schedule_{NNI}_{date}.pdf`
- **المحتوى:** ملف PDF يحتوي على الجدول الزمني منسق للطباعة

**مثال على استجابة الخطأ | Error Response:**
```json
{
    "success": false,
    "message": "الطالب غير موجود"
}
```

**ملاحظات مهمة | Important Notes:**
- الملف يتم إنشاؤه ديناميكياً بناءً على الجدول الحالي للطالب
- يحتوي على معلومات الطالب والصف
- الجدول منسق للطباعة بصيغة landscape
- يحتوي على header وfooter بالمعلومات الأساسية

---

### 6. تسجيل الخروج | Logout

**Endpoint:** `POST /api/student/logout`

**الغرض:** إلغاء الـ token وتسجيل خروج الطالب

**Authentication:** مطلوب Bearer Token

**مثال على الطلب | Request Example:**
```bash
curl -X POST "http://172.20.10.4:8000/api/student/logout" \
-H "Authorization: Bearer 1|abc123def456ghi789..." \
-H "Content-Type: application/json"
```

**الاستجابة | Response:**
```json
{
    "success": true,
    "message": "تم تسجيل الخروج بنجاح"
}
```

---

## 🔧 معالجة الأخطاء | Error Handling

### رموز الحالة الشائعة | Common Status Codes

- **200**: نجح الطلب
- **401**: غير مصرح (Token غير صالح أو منتهي الصلاحية)
- **404**: المورد غير موجود
- **422**: بيانات غير صالحة
- **500**: خطأ في الخادم

### هيكل رسالة الخطأ | Error Response Structure
```json
{
    "success": false,
    "message": "وصف الخطأ هنا"
}
```

### أمثلة على الأخطاء | Error Examples

**Token منتهي الصلاحية:**
```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

**بيانات ناقصة:**
```json
{
    "success": false,
    "message": "The nni field is required."
}
```

---

## 📋 تطبيق الـ API في Flutter/React Native

### 1. تسجيل الدخول

#### Flutter Example:
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  static const String baseUrl = 'http://172.20.10.4:8000/api';
  
  static Future<Map<String, dynamic>> login(String nni, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/student/login'),
        headers: {
          'Content-Type': 'application/json',
        },
        body: jsonEncode({
          'nni': nni,
          'password': password,
        }),
      );

      return jsonDecode(response.body);
    } catch (e) {
      return {
        'success': false,
        'message': 'خطأ في الاتصال بالخادم'
      };
    }
  }
}
```

#### React Native Example:
```javascript
class ApiService {
  static baseUrl = 'http://172.20.10.4:8000/api';

  static async login(nni, password) {
    try {
      const response = await fetch(`${this.baseUrl}/student/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nni: nni,
          password: password,
        }),
      });

      return await response.json();
    } catch (error) {
      return {
        success: false,
        message: 'خطأ في الاتصال بالخادم'
      };
    }
  }
}
```

### 2. جلب الجدول الزمني

#### Flutter Example:
```dart
static Future<Map<String, dynamic>> getSchedule(String token) async {
  try {
    final response = await http.get(
      Uri.parse('$baseUrl/student/schedule'),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    return jsonDecode(response.body);
  } catch (e) {
    return {
      'success': false,
      'message': 'خطأ في جلب الجدول الزمني'
    };
  }
}
```

---

## 🔐 الأمان | Security

### 1. إدارة الـ Tokens
- احفظ الـ token بشكل آمن في التطبيق (SharedPreferences في Flutter، AsyncStorage في React Native)
- تحقق من انتهاء صلاحية الـ token وأعد توجيه المستخدم لتسجيل الدخول عند الحاجة
- احذف الـ token عند تسجيل الخروج

### 2. التحقق من الاستجابات
```dart
if (response['success'] == true) {
  // نجح الطلب
  var data = response['data'];
} else {
  // فشل الطلب
  showError(response['message']);
}
```

---

## 📊 هيكل البيانات | Data Structure

### Student Object
```json
{
  "id": 1,
  "nni": "string",           // رقم التعريف الوطني
  "fullname": "string",      // الاسم الكامل
  "parent_name": "string",   // اسم ولي الأمر
  "phone": "string",         // رقم الهاتف
  "image": "url|null",       // رابط الصورة (قد يكون null)
  "class": {
    "id": 1,
    "nom": "string",         // اسم الفصل
    "niveau": "string",      // المستوى
    "specialite": "string",  // التخصص
    "annee": "string"        // السنة الدراسية
  }
}
```

### Schedule Object
```json
{
  "class_info": {
    "id": 1,
    "nom": "string"
  },
  "schedule": {
    "يوم الأسبوع": [
      {
        "id": 1,
        "subject": {
          "id": 1,
          "name": "string"
        },
        "teacher": {
          "id": 1,
          "name": "string"
        },
        "trimester": {
          "id": 1,
          "name": "string"
        },
        "horaires": [
          {
            "id": 1,
            "start_time": "HH:MM",
            "end_time": "HH:MM",
            "libelle_fr": "string",
            "libelle_ar": "string"
          }
        ]
      }
    ]
  }
}
```

---

## 🚀 اختبار API

### استخدام Postman
1. أنشئ Collection جديد
2. أضف الـ requests الأربعة
3. استخدم Environment variables للـ base URL و token

### استخدام cURL
تم تضمين أمثلة cURL مع كل endpoint أعلاه

---

## 📞 التواصل والدعم | Support

في حال وجود أي مشاكل أو استفسارات:

- **البريد الإلكتروني**: support@yourapp.com
- **الهاتف**: +222 XX XX XX XX
- **ساعات الدعم**: الأحد - الخميس، 8:00 ص - 5:00 م

---

## 📝 ملاحظات مهمة | Important Notes

1. **كلمة المرور الافتراضية**: عند إنشاء حساب طالب جديد، تكون كلمة المرور هي نفس رقم التعريف الوطني (NNI)
2. **الصور**: قد تكون الصور null، تأكد من التعامل مع هذه الحالة في التطبيق
3. **الجدول الزمني**: يتم تجميع الحصص حسب أيام الأسبوع
4. **الأوقات**: تُعرض بصيغة 24 ساعة (HH:MM)
5. **اللغة**: النظام يدعم اللغتين العربية والفرنسية

---

## 🔄 تحديثات مستقبلية | Future Updates

الميزات القادمة:
- نظام الإشعارات
- تحديث كلمة المرور
- عرض الدرجات
- نظام الواجبات المنزلية
- تتبع الحضور والغياب

---

**إعداد**: فريق التطوير - نوفمبر 2024  
**الإصدار**: 1.0.0
