# 🔧 حل مشكلة اتصال التطبيق بـ Laravel API

## 🚨 المشكلة المحددة

التطبيق يرسل طلبات إلى:
```
POST http://172.20.10.4:8000/student/login
```

بينما الـ API الصحيح هو:
```
POST http://172.20.10.4:8000/api/student/login
```

**المشكلة:** نقص `/api` في بداية المسار.

## ✅ التحقق من Laravel (مكتمل)

- [x] **Routes:** موجودة في `routes/api.php`
- [x] **Bootstrap:** مضبوط في `bootstrap/app.php` 
- [x] **Controller:** `StudentApiController` يعمل بشكل صحيح
- [x] **Server:** Laravel يعمل على `http://172.20.10.4:8000`

## 🔧 الحل المطلوب في التطبيق

### 1. تحديث Base URL

#### في React Native:
```javascript
// config/api.js أو constants/api.js
// ❌ الإعداد الخاطئ:
export const BASE_URL = 'http://172.20.10.4:8000';

// ✅ الإعداد الصحيح:
export const BASE_URL = 'http://172.20.10.4:8000/api';

// أو بطريقة أفضل:
export const API_CONFIG = {
  BASE_URL: 'http://172.20.10.4:8000',
  API_PREFIX: '/api',
  get API_BASE() {
    return this.BASE_URL + this.API_PREFIX;
  }
};
```

#### في Flutter/Dart:
```dart
// lib/services/api_service.dart
class ApiService {
  // ❌ الإعداد الخاطئ:
  static const String baseUrl = 'http://172.20.10.4:8000';
  
  // ✅ الإعداد الصحيح:
  static const String baseUrl = 'http://172.20.10.4:8000/api';
  
  // أو:
  static const String serverUrl = 'http://172.20.10.4:8000';
  static const String apiPrefix = '/api';
  static String get baseUrl => serverUrl + apiPrefix;
}
```

### 2. تحديث Endpoints

#### قبل (خاطئ):
```javascript
const endpoints = {
  LOGIN: '/student/login',
  PROFILE: '/student/profile',
  SCHEDULE: '/student/schedule'
};

// النتيجة: http://172.20.10.4:8000/student/login ❌
const loginUrl = BASE_URL + endpoints.LOGIN;
```

#### بعد (صحيح):
```javascript
const BASE_URL = 'http://172.20.10.4:8000/api';

const endpoints = {
  LOGIN: '/student/login',
  PROFILE: '/student/profile', 
  SCHEDULE: '/student/schedule'
};

// النتيجة: http://172.20.10.4:8000/api/student/login ✅
const loginUrl = BASE_URL + endpoints.LOGIN;
```

### 3. أمثلة كاملة للاستخدام

#### React Native مع Axios:
```javascript
import axios from 'axios';

const API_BASE_URL = 'http://172.20.10.4:8000/api';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// تسجيل الدخول
export const login = async (nni, password) => {
  try {
    const response = await apiClient.post('/student/login', {
      nni,
      password
    });
    return response.data;
  } catch (error) {
    throw error;
  }
};

// جلب الملف الشخصي
export const getProfile = async (token) => {
  try {
    const response = await apiClient.get('/student/profile', {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });
    return response.data;
  } catch (error) {
    throw error;
  }
};
```

#### Flutter مع http:
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class StudentApiService {
  static const String baseUrl = 'http://172.20.10.4:8000/api';
  
  // تسجيل الدخول
  static Future<Map<String, dynamic>> login(String nni, String password) async {
    final url = Uri.parse('$baseUrl/student/login');
    
    final response = await http.post(
      url,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: json.encode({
        'nni': nni,
        'password': password,
      }),
    );
    
    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('فشل في تسجيل الدخول');
    }
  }
  
  // جلب الملف الشخصي
  static Future<Map<String, dynamic>> getProfile(String token) async {
    final url = Uri.parse('$baseUrl/student/profile');
    
    final response = await http.get(
      url,
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );
    
    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('فشل في جلب الملف الشخصي');
    }
  }
}
```

## 📋 قائمة الـ Endpoints الصحيحة

| الوظيفة | Method | Endpoint الصحيح |
|---------|--------|------------------|
| تسجيل الدخول | POST | `/api/student/login` |
| الملف الشخصي | GET | `/api/student/profile` |
| الجدول العادي | GET | `/api/student/schedule` |
| بيانات الجدول | GET | `/api/student/schedule/data` |
| تحميل PDF | GET | `/api/student/schedule/pdf` |
| تسجيل الخروج | POST | `/api/student/logout` |

## 🧪 اختبار الحل

### اختبار سريع:
```bash
# اختبار تسجيل الدخول
curl -X POST "http://172.20.10.4:8000/api/student/login" \
  -H "Content-Type: application/json" \
  -d '{"nni":"1234567890","password":"1234567890"}'
```

### مع JavaScript:
```javascript
fetch('http://172.20.10.4:8000/api/student/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    nni: '1234567890',
    password: '1234567890'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

## 📁 ملفات للتحقق في التطبيق

ابحث عن هذه الملفات وحدث BASE_URL فيها:

### React Native:
- `src/config/api.js`
- `src/constants/api.js`
- `src/services/apiService.js`
- `src/utils/api.js`

### Flutter:
- `lib/services/api_service.dart`
- `lib/config/api_config.dart`
- `lib/constants/api_constants.dart`
- `lib/utils/api_client.dart`

## ⚡ حل سريع

إذا كان لديك متغير واحد فقط للـ BASE_URL، فقط أضف `/api`:

```
من: http://172.20.10.4:8000
إلى: http://172.20.10.4:8000/api
```

---

**💡 ملاحظة:** بعد التحديث، تأكد من إعادة تشغيل التطبيق وحذف أي cache للتأكد من تطبيق التغييرات.
