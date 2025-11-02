# أكواد جاهزة للتطبيق الجوال
# Ready-to-Use Mobile App Code Examples

## 📱 Flutter Implementation

### Dependencies المطلوبة | Required Dependencies

أضف هذه المكتبات إلى `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  shared_preferences: ^2.2.2
  path_provider: ^2.1.1
  open_file: ^3.3.2

dev_dependencies:
  flutter_test:
    sdk: flutter
```

### 1. ملف خدمة API | API Service File

```dart
// lib/services/api_service.dart
import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:path_provider/path_provider.dart';

class ApiService {
  static const String baseUrl = 'http://172.20.10.4:8000/api';
  static const String tokenKey = 'student_token';

  // حفظ الـ Token
  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(tokenKey, token);
  }

  // جلب الـ Token
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(tokenKey);
  }

  // حذف الـ Token
  static Future<void> removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(tokenKey);
  }

  // تسجيل الدخول
  static Future<ApiResponse> login(String nni, String password) async {
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

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200 && data['success'] == true) {
        // حفظ الـ Token
        await saveToken(data['data']['token']);
        return ApiResponse.success(data['data']);
      } else {
        return ApiResponse.error(data['message'] ?? 'خطأ غير معروف');
      }
    } catch (e) {
      return ApiResponse.error('خطأ في الاتصال بالخادم');
    }
  }

  // جلب الملف الشخصي
  static Future<ApiResponse> getProfile() async {
    try {
      final token = await getToken();
      if (token == null) {
        return ApiResponse.error('يجب تسجيل الدخول أولاً');
      }

      final response = await http.get(
        Uri.parse('$baseUrl/student/profile'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200 && data['success'] == true) {
        return ApiResponse.success(data['data']);
      } else if (response.statusCode == 401) {
        // Token منتهي الصلاحية
        await removeToken();
        return ApiResponse.error('انتهت جلسة المستخدم، يرجى تسجيل الدخول مرة أخرى');
      } else {
        return ApiResponse.error(data['message'] ?? 'خطأ غير معروف');
      }
    } catch (e) {
      return ApiResponse.error('خطأ في الاتصال بالخادم');
    }
  }

  // جلب الجدول الزمني
  static Future<ApiResponse> getSchedule() async {
    try {
      final token = await getToken();
      if (token == null) {
        return ApiResponse.error('يجب تسجيل الدخول أولاً');
      }

      final response = await http.get(
        Uri.parse('$baseUrl/student/schedule'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200 && data['success'] == true) {
        return ApiResponse.success(data['data']);
      } else if (response.statusCode == 401) {
        await removeToken();
        return ApiResponse.error('انتهت جلسة المستخدم، يرجى تسجيل الدخول مرة أخرى');
      } else {
        return ApiResponse.error(data['message'] ?? 'خطأ غير معروف');
      }
    } catch (e) {
      return ApiResponse.error('خطأ في الاتصال بالخادم');
    }
  }

  // تحميل الجدول الزمني كـ PDF
  static Future<ApiResponse> downloadSchedulePdf() async {
    try {
      final token = await getToken();
      if (token == null) {
        return ApiResponse.error('يجب تسجيل الدخول أولاً');
      }

      final response = await http.get(
        Uri.parse('$baseUrl/student/schedule/pdf'),
        headers: {
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        // حفظ الملف محلياً
        final directory = await getApplicationDocumentsDirectory();
        final file = File('${directory.path}/student_schedule_${DateTime.now().millisecondsSinceEpoch}.pdf');
        await file.writeAsBytes(response.bodyBytes);
        
        return ApiResponse.success({
          'message': 'تم تحميل الجدول بنجاح',
          'filePath': file.path
        });
      } else if (response.statusCode == 401) {
        await removeToken();
        return ApiResponse.error('انتهت جلسة المستخدم، يرجى تسجيل الدخول مرة أخرى');
      } else {
        final data = jsonDecode(response.body);
        return ApiResponse.error(data['message'] ?? 'خطأ في تحميل الملف');
      }
    } catch (e) {
      return ApiResponse.error('خطأ في تحميل الملف: $e');
    }
  }

  // تسجيل الخروج
  static Future<ApiResponse> logout() async {
    try {
      final token = await getToken();
      if (token == null) {
        return ApiResponse.success(null);
      }

      final response = await http.post(
        Uri.parse('$baseUrl/student/logout'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      await removeToken();
      
      if (response.statusCode == 200) {
        return ApiResponse.success(null);
      } else {
        // حتى لو فشل الطلب، نحذف الـ Token محلياً
        return ApiResponse.success(null);
      }
    } catch (e) {
      // حتى لو حدث خطأ، نحذف الـ Token محلياً
      await removeToken();
      return ApiResponse.success(null);
    }
  }
}

// كلاس للاستجابات
class ApiResponse {
  final bool success;
  final dynamic data;
  final String? error;

  ApiResponse.success(this.data) : success = true, error = null;
  ApiResponse.error(this.error) : success = false, data = null;
}
```

### 2. نماذج البيانات | Data Models

```dart
// lib/models/student.dart
class Student {
  final int id;
  final String nni;
  final String fullname;
  final String parentName;
  final String phone;
  final String? image;
  final StudentClass studentClass;

  Student({
    required this.id,
    required this.nni,
    required this.fullname,
    required this.parentName,
    required this.phone,
    this.image,
    required this.studentClass,
  });

  factory Student.fromJson(Map<String, dynamic> json) {
    return Student(
      id: json['id'],
      nni: json['nni'],
      fullname: json['fullname'],
      parentName: json['parent_name'],
      phone: json['phone'],
      image: json['image'],
      studentClass: StudentClass.fromJson(json['class']),
    );
  }

  // جلب رابط الصورة أو صورة افتراضية
  String get imageUrl {
    return image ?? 'https://via.placeholder.com/150?text=طالب';
  }
}

class StudentClass {
  final int id;
  final String nom;
  final String? niveau;
  final String? specialite;
  final String? annee;

  StudentClass({
    required this.id,
    required this.nom,
    this.niveau,
    this.specialite,
    this.annee,
  });

  factory StudentClass.fromJson(Map<String, dynamic> json) {
    return StudentClass(
      id: json['id'],
      nom: json['nom'],
      niveau: json['niveau'],
      specialite: json['specialite'],
      annee: json['annee'],
    );
  }

  String get fullName {
    List<String> parts = [nom];
    if (niveau != null) parts.add(niveau!);
    if (specialite != null) parts.add(specialite!);
    return parts.join(' - ');
  }
}
```

```dart
// lib/models/schedule.dart
class Schedule {
  final ClassInfo classInfo;
  final Map<String, List<ScheduleItem>> weeklySchedule;

  Schedule({
    required this.classInfo,
    required this.weeklySchedule,
  });

  factory Schedule.fromJson(Map<String, dynamic> json) {
    final scheduleData = json['schedule'] as Map<String, dynamic>;
    final Map<String, List<ScheduleItem>> weeklySchedule = {};

    scheduleData.forEach((day, items) {
      weeklySchedule[day] = (items as List)
          .map((item) => ScheduleItem.fromJson(item))
          .toList();
    });

    return Schedule(
      classInfo: ClassInfo.fromJson(json['class_info']),
      weeklySchedule: weeklySchedule,
    );
  }

  // ترتيب أيام الأسبوع
  List<String> get sortedDays {
    const daysOrder = [
      'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 
      'الخميس', 'الجمعة', 'السبت'
    ];
    
    return daysOrder.where((day) => weeklySchedule.containsKey(day)).toList();
  }
}

class ClassInfo {
  final int id;
  final String nom;

  ClassInfo({required this.id, required this.nom});

  factory ClassInfo.fromJson(Map<String, dynamic> json) {
    return ClassInfo(
      id: json['id'],
      nom: json['nom'],
    );
  }
}

class ScheduleItem {
  final int id;
  final Subject subject;
  final Teacher teacher;
  final Trimester trimester;
  final List<Horaire> horaires;

  ScheduleItem({
    required this.id,
    required this.subject,
    required this.teacher,
    required this.trimester,
    required this.horaires,
  });

  factory ScheduleItem.fromJson(Map<String, dynamic> json) {
    return ScheduleItem(
      id: json['id'],
      subject: Subject.fromJson(json['subject']),
      teacher: Teacher.fromJson(json['teacher']),
      trimester: Trimester.fromJson(json['trimester']),
      horaires: (json['horaires'] as List)
          .map((h) => Horaire.fromJson(h))
          .toList(),
    );
  }

  String get timeRange {
    if (horaires.isEmpty) return '';
    if (horaires.length == 1) {
      return '${horaires.first.startTime} - ${horaires.first.endTime}';
    }
    return '${horaires.first.startTime} - ${horaires.last.endTime}';
  }
}

class Subject {
  final int id;
  final String name;

  Subject({required this.id, required this.name});

  factory Subject.fromJson(Map<String, dynamic> json) {
    return Subject(id: json['id'], name: json['name']);
  }
}

class Teacher {
  final int id;
  final String name;

  Teacher({required this.id, required this.name});

  factory Teacher.fromJson(Map<String, dynamic> json) {
    return Teacher(id: json['id'], name: json['name']);
  }
}

class Trimester {
  final int id;
  final String name;

  Trimester({required this.id, required this.name});

  factory Trimester.fromJson(Map<String, dynamic> json) {
    return Trimester(id: json['id'], name: json['name']);
  }
}

class Horaire {
  final int id;
  final String startTime;
  final String endTime;
  final String libelleFr;
  final String libelleAr;

  Horaire({
    required this.id,
    required this.startTime,
    required this.endTime,
    required this.libelleFr,
    required this.libelleAr,
  });

  factory Horaire.fromJson(Map<String, dynamic> json) {
    return Horaire(
      id: json['id'],
      startTime: json['start_time'],
      endTime: json['end_time'],
      libelleFr: json['libelle_fr'],
      libelleAr: json['libelle_ar'],
    );
  }
}
```

### 3. صفحة تسجيل الدخول | Login Page

```dart
// lib/pages/login_page.dart
import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/student.dart';

class LoginPage extends StatefulWidget {
  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final _nniController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;

  @override
  void dispose() {
    _nniController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isLoading = true;
    });

    final response = await ApiService.login(
      _nniController.text.trim(),
      _passwordController.text.trim(),
    );

    setState(() {
      _isLoading = false;
    });

    if (response.success) {
      final student = Student.fromJson(response.data['student']);
      Navigator.pushReplacementNamed(
        context,
        '/home',
        arguments: student,
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response.error!),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('تسجيل الدخول'),
        centerTitle: true,
      ),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              // شعار التطبيق
              Icon(
                Icons.school,
                size: 80,
                color: Theme.of(context).primaryColor,
              ),
              SizedBox(height: 32),

              // حقل رقم التعريف الوطني
              TextFormField(
                controller: _nniController,
                decoration: InputDecoration(
                  labelText: 'رقم التعريف الوطني',
                  border: OutlineInputBorder(),
                  prefixIcon: Icon(Icons.card_membership),
                ),
                keyboardType: TextInputType.number,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'يرجى إدخال رقم التعريف الوطني';
                  }
                  if (value.length < 8) {
                    return 'رقم التعريف يجب أن يكون 8 أرقام على الأقل';
                  }
                  return null;
                },
              ),
              SizedBox(height: 16),

              // حقل كلمة المرور
              TextFormField(
                controller: _passwordController,
                decoration: InputDecoration(
                  labelText: 'كلمة المرور',
                  border: OutlineInputBorder(),
                  prefixIcon: Icon(Icons.lock),
                ),
                obscureText: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'يرجى إدخال كلمة المرور';
                  }
                  return null;
                },
              ),
              SizedBox(height: 24),

              // زر تسجيل الدخول
              SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton(
                  onPressed: _isLoading ? null : _login,
                  child: _isLoading
                      ? CircularProgressIndicator(color: Colors.white)
                      : Text('تسجيل الدخول'),
                ),
              ),

              SizedBox(height: 16),

              // نصائح للمستخدم
              Card(
                child: Padding(
                  padding: EdgeInsets.all(12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'معلومات مهمة:',
                        style: TextStyle(fontWeight: FontWeight.bold),
                      ),
                      SizedBox(height: 8),
                      Text('• كلمة المرور الافتراضية هي نفس رقم التعريف الوطني'),
                      Text('• يمكنك تغيير كلمة المرور من الإعدادات'),
                      Text('• في حال نسيان كلمة المرور، اتصل بالإدارة'),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
```

### 4. صفحة الجدول الزمني | Schedule Page

```dart
// lib/pages/schedule_page.dart
import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/schedule.dart';

class SchedulePage extends StatefulWidget {
  @override
  _SchedulePageState createState() => _SchedulePageState();
}

class _SchedulePageState extends State<SchedulePage> {
  Schedule? _schedule;
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _loadSchedule();
  }

  Future<void> _loadSchedule() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    final response = await ApiService.getSchedule();

    setState(() {
      _isLoading = false;
      if (response.success) {
        _schedule = Schedule.fromJson(response.data);
      } else {
        _error = response.error;
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('الجدول الزمني'),
        actions: [
          IconButton(
            icon: Icon(Icons.refresh),
            onPressed: _loadSchedule,
          ),
        ],
      ),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (_isLoading) {
      return Center(child: CircularProgressIndicator());
    }

    if (_error != null) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.error, size: 64, color: Colors.red),
            SizedBox(height: 16),
            Text(_error!),
            SizedBox(height: 16),
            ElevatedButton(
              onPressed: _loadSchedule,
              child: Text('إعادة المحاولة'),
            ),
          ],
        ),
      );
    }

    if (_schedule == null || _schedule!.weeklySchedule.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.schedule, size: 64, color: Colors.grey),
            SizedBox(height: 16),
            Text('لا يوجد جدول زمني متاح'),
          ],
        ),
      );
    }

    return Column(
      children: [
        // معلومات الفصل
        Container(
          width: double.infinity,
          padding: EdgeInsets.all(16),
          color: Theme.of(context).primaryColor.withOpacity(0.1),
          child: Text(
            'فصل: ${_schedule!.classInfo.nom}',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
            textAlign: TextAlign.center,
          ),
        ),

        // الجدول الزمني
        Expanded(
          child: ListView.builder(
            itemCount: _schedule!.sortedDays.length,
            itemBuilder: (context, index) {
              final day = _schedule!.sortedDays[index];
              final daySchedule = _schedule!.weeklySchedule[day]!;
              
              return _buildDayCard(day, daySchedule);
            },
          ),
        ),
      ],
    );
  }

  Widget _buildDayCard(String day, List<ScheduleItem> scheduleItems) {
    return Card(
      margin: EdgeInsets.all(8),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // عنوان اليوم
          Container(
            width: double.infinity,
            padding: EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Theme.of(context).primaryColor,
              borderRadius: BorderRadius.only(
                topLeft: Radius.circular(4),
                topRight: Radius.circular(4),
              ),
            ),
            child: Text(
              day,
              style: TextStyle(
                color: Colors.white,
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),

          // قائمة الحصص
          ...scheduleItems.map((item) => _buildScheduleItem(item)).toList(),
        ],
      ),
    );
  }

  Widget _buildScheduleItem(ScheduleItem item) {
    return ListTile(
      leading: CircleAvatar(
        child: Text(
          item.timeRange.split(' - ')[0], // الوقت الأول فقط
          style: TextStyle(fontSize: 10),
        ),
      ),
      title: Text(item.subject.name),
      subtitle: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('المدرس: ${item.teacher.name}'),
          Text('الوقت: ${item.timeRange}'),
          if (item.trimester.name.isNotEmpty)
            Text('الفصل: ${item.trimester.name}'),
        ],
      ),
      isThreeLine: true,
    );
  }
}
```

### 4. صفحة الجدول الزمني مع إمكانية التحميل | Schedule Page with PDF Download

```dart
// lib/pages/schedule_page.dart
import 'package:flutter/material.dart';
import 'package:open_file/open_file.dart';
import '../services/api_service.dart';
import '../models/schedule.dart';

class SchedulePage extends StatefulWidget {
  @override
  _SchedulePageState createState() => _SchedulePageState();
}

class _SchedulePageState extends State<SchedulePage> {
  bool _isLoading = false;
  bool _isDownloading = false;
  WeeklySchedule? _schedule;

  @override
  void initState() {
    super.initState();
    _loadSchedule();
  }

  Future<void> _loadSchedule() async {
    setState(() => _isLoading = true);
    
    final response = await ApiService.getSchedule();
    if (response.success) {
      setState(() {
        _schedule = WeeklySchedule.fromJson(response.data);
        _isLoading = false;
      });
    } else {
      setState(() => _isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(response.message)),
      );
    }
  }

  Future<void> _downloadPdf() async {
    setState(() => _isDownloading = true);
    
    final response = await ApiService.downloadSchedulePdf();
    setState(() => _isDownloading = false);
    
    if (response.success) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('تم تحميل الجدول بنجاح'),
          action: SnackBarAction(
            label: 'فتح الملف',
            onPressed: () => OpenFile.open(response.data['filePath']),
          ),
        ),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(response.message)),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('الجدول الزمني'),
        actions: [
          IconButton(
            icon: _isDownloading 
                ? CircularProgressIndicator(color: Colors.white)
                : Icon(Icons.download),
            onPressed: _isDownloading ? null : _downloadPdf,
            tooltip: 'تحميل كـ PDF',
          ),
        ],
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _schedule == null
              ? Center(child: Text('لا يوجد جدول زمني'))
              : ListView.builder(
                  itemCount: _schedule!.schedule.keys.length,
                  itemBuilder: (context, index) {
                    final day = _schedule!.schedule.keys.elementAt(index);
                    final classes = _schedule!.schedule[day]!;
                    
                    return Card(
                      margin: EdgeInsets.all(8),
                      child: ExpansionTile(
                        title: Text(
                          day,
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 18,
                          ),
                        ),
                        children: classes.map((classItem) => 
                          ListTile(
                            leading: CircleAvatar(
                              child: Text(classItem.subject.name[0]),
                              backgroundColor: Colors.blue,
                              foregroundColor: Colors.white,
                            ),
                            title: Text(classItem.subject.name),
                            subtitle: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text('المعلم: ${classItem.teacher.name}'),
                                Text('الوقت: ${classItem.horaires.map((h) => h.libelleAr).join(', ')}'),
                                Text('الفصل: ${classItem.trimester.name}'),
                              ],
                            ),
                          )
                        ).toList(),
                      ),
                    );
                  },
                ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _isDownloading ? null : _downloadPdf,
        icon: _isDownloading 
            ? SizedBox(
                width: 20, 
                height: 20,
                child: CircularProgressIndicator(strokeWidth: 2),
              )
            : Icon(Icons.picture_as_pdf),
        label: Text(_isDownloading ? 'جاري التحميل...' : 'تحميل PDF'),
      ),
    );
  }
}
```

---

## 📱 React Native Implementation

### 1. خدمة API | API Service

```javascript
// services/ApiService.js
import AsyncStorage from '@react-native-async-storage/async-storage';

class ApiService {
  static baseUrl = 'http://172.20.10.4:8000/api';
  static tokenKey = 'student_token';

  // حفظ الـ Token
  static async saveToken(token) {
    try {
      await AsyncStorage.setItem(this.tokenKey, token);
    } catch (error) {
      console.error('Error saving token:', error);
    }
  }

  // جلب الـ Token
  static async getToken() {
    try {
      return await AsyncStorage.getItem(this.tokenKey);
    } catch (error) {
      console.error('Error getting token:', error);
      return null;
    }
  }

  // حذف الـ Token
  static async removeToken() {
    try {
      await AsyncStorage.removeItem(this.tokenKey);
    } catch (error) {
      console.error('Error removing token:', error);
    }
  }

  // تسجيل الدخول
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

      const data = await response.json();
      
      if (response.ok && data.success) {
        await this.saveToken(data.data.token);
        return { success: true, data: data.data };
      } else {
        return { success: false, error: data.message || 'خطأ غير معروف' };
      }
    } catch (error) {
      return { success: false, error: 'خطأ في الاتصال بالخادم' };
    }
  }

  // جلب الملف الشخصي
  static async getProfile() {
    try {
      const token = await this.getToken();
      if (!token) {
        return { success: false, error: 'يجب تسجيل الدخول أولاً' };
      }

      const response = await fetch(`${this.baseUrl}/student/profile`, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
      });

      const data = await response.json();
      
      if (response.ok && data.success) {
        return { success: true, data: data.data };
      } else if (response.status === 401) {
        await this.removeToken();
        return { success: false, error: 'انتهت جلسة المستخدم، يرجى تسجيل الدخول مرة أخرى' };
      } else {
        return { success: false, error: data.message || 'خطأ غير معروف' };
      }
    } catch (error) {
      return { success: false, error: 'خطأ في الاتصال بالخادم' };
    }
  }

  // جلب الجدول الزمني
  static async getSchedule() {
    try {
      const token = await this.getToken();
      if (!token) {
        return { success: false, error: 'يجب تسجيل الدخول أولاً' };
      }

      const response = await fetch(`${this.baseUrl}/student/schedule`, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
      });

      const data = await response.json();
      
      if (response.ok && data.success) {
        return { success: true, data: data.data };
      } else if (response.status === 401) {
        await this.removeToken();
        return { success: false, error: 'انتهت جلسة المستخدم، يرجى تسجيل الدخول مرة أخرى' };
      } else {
        return { success: false, error: data.message || 'خطأ غير معروف' };
      }
    } catch (error) {
      return { success: false, error: 'خطأ في الاتصال بالخادم' };
    }
  }

  // تحميل الجدول الزمني كـ PDF
  static async downloadSchedulePdf() {
    try {
      const token = await this.getToken();
      if (!token) {
        return { success: false, error: 'يجب تسجيل الدخول أولاً' };
      }

      const response = await fetch(`${this.baseUrl}/student/schedule/pdf`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
        },
      });

      if (response.ok) {
        const blob = await response.blob();
        // يمكن استخدام مكتبة مثل react-native-fs لحفظ الملف
        return { 
          success: true, 
          data: { 
            blob: blob,
            message: 'تم تحميل الجدول بنجاح'
          }
        };
      } else if (response.status === 401) {
        await this.removeToken();
        return { success: false, error: 'انتهت جلسة المستخدم، يرجى تسجيل الدخول مرة أخرى' };
      } else {
        const data = await response.json();
        return { success: false, error: data.message || 'خطأ في تحميل الملف' };
      }
    } catch (error) {
      return { success: false, error: 'خطأ في تحميل الملف' };
    }
  }

  // تسجيل الخروج
  static async logout() {
    try {
      const token = await this.getToken();
      if (token) {
        await fetch(`${this.baseUrl}/student/logout`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
          },
        });
      }
      
      await this.removeToken();
      return { success: true };
    } catch (error) {
      await this.removeToken();
      return { success: true };
    }
  }
}

export default ApiService;
```

### 2. شاشة تسجيل الدخول | Login Screen

```javascript
// screens/LoginScreen.js
import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Alert,
  ActivityIndicator,
} from 'react-native';
import ApiService from '../services/ApiService';

const LoginScreen = ({ navigation }) => {
  const [nni, setNni] = useState('');
  const [password, setPassword] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const handleLogin = async () => {
    if (!nni.trim() || !password.trim()) {
      Alert.alert('خطأ', 'يرجى إدخال جميع البيانات المطلوبة');
      return;
    }

    setIsLoading(true);
    
    const response = await ApiService.login(nni.trim(), password.trim());
    
    setIsLoading(false);

    if (response.success) {
      navigation.replace('Home', { student: response.data.student });
    } else {
      Alert.alert('خطأ في تسجيل الدخول', response.error);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>تسجيل الدخول</Text>
      
      <TextInput
        style={styles.input}
        placeholder="رقم التعريف الوطني"
        value={nni}
        onChangeText={setNni}
        keyboardType="numeric"
      />
      
      <TextInput
        style={styles.input}
        placeholder="كلمة المرور"
        value={password}
        onChangeText={setPassword}
        secureTextEntry
      />
      
      <TouchableOpacity 
        style={styles.button} 
        onPress={handleLogin}
        disabled={isLoading}
      >
        {isLoading ? (
          <ActivityIndicator color="white" />
        ) : (
          <Text style={styles.buttonText}>تسجيل الدخول</Text>
        )}
      </TouchableOpacity>

      <View style={styles.infoCard}>
        <Text style={styles.infoTitle}>معلومات مهمة:</Text>
        <Text style={styles.infoText}>• كلمة المرور الافتراضية هي نفس رقم التعريف الوطني</Text>
        <Text style={styles.infoText}>• يمكنك تغيير كلمة المرور من الإعدادات</Text>
        <Text style={styles.infoText}>• في حال نسيان كلمة المرور، اتصل بالإدارة</Text>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    justifyContent: 'center',
    backgroundColor: '#f5f5f5',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: 30,
    color: '#333',
  },
  input: {
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
    padding: 15,
    marginBottom: 15,
    backgroundColor: 'white',
    fontSize: 16,
  },
  button: {
    backgroundColor: '#007AFF',
    padding: 15,
    borderRadius: 8,
    alignItems: 'center',
    marginTop: 10,
  },
  buttonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: 'bold',
  },
  infoCard: {
    backgroundColor: 'white',
    padding: 15,
    borderRadius: 8,
    marginTop: 20,
    borderLeftWidth: 3,
    borderLeftColor: '#007AFF',
  },
  infoTitle: {
    fontWeight: 'bold',
    marginBottom: 5,
    color: '#333',
  },
  infoText: {
    color: '#666',
    marginBottom: 2,
  },
});

export default LoginScreen;
```

---

## 🔧 نصائح التطوير | Development Tips

### 1. معالجة الأخطاء
```dart
// Flutter
try {
  final response = await ApiService.getSchedule();
  if (response.success) {
    // نجح الطلب
  } else {
    // فشل الطلب - أظهر رسالة خطأ
    showErrorDialog(response.error!);
  }
} catch (e) {
  // خطأ غير متوقع
  showErrorDialog('خطأ غير متوقع: $e');
}
```

### 2. إدارة الـ Loading States
```dart
// Flutter
class MyWidget extends StatefulWidget {
  bool _isLoading = false;
  
  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    
    // استدعاء API
    final response = await ApiService.getProfile();
    
    setState(() => _isLoading = false);
    
    // معالجة النتيجة
  }
}
```

### 3. إعادة المحاولة التلقائية
```dart
// Flutter
Future<ApiResponse> _retryRequest(Function apiCall, {int maxRetries = 3}) async {
  for (int i = 0; i < maxRetries; i++) {
    final response = await apiCall();
    if (response.success) return response;
    
    if (i < maxRetries - 1) {
      await Future.delayed(Duration(seconds: 2)); // انتظر ثانيتين
    }
  }
  
  return ApiResponse.error('فشل بعد عدة محاولات');
}
```

---

**آخر تحديث**: نوفمبر 2024  
**إعداد**: فريق تطوير النظام
