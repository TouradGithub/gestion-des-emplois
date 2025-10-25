# نظام إدارة حضور الأساتذة - Système de Gestion des Pointages

## نظرة عامة / Vue d'ensemble

تم إضافة نظام شامل لإدارة حضور الأساتذة إلى نظام إدارة الجداول الدراسية في Laravel. يدعم النظام اللغتين العربية والفرنسية ويوفر واجهة متكاملة لتسجيل ومتابعة حضور الأساتذة.

Un système complet de gestion des pointages des enseignants a été ajouté au système de gestion des emplois du temps Laravel. Le système supporte l'arabe et le français et fournit une interface intégrée pour l'enregistrement et le suivi de la présence des enseignants.

## المميزات المضافة / Fonctionnalités Ajoutées

### 1. قاعدة البيانات / Base de données
- ✅ جدول `pointages` مع جميع العلاقات المطلوبة
- ✅ Migration كاملة مع foreign keys وconstraints
- ✅ Model Eloquent مع العلاقات والـ scopes

### 2. النماذج والعلاقات / Modèles et Relations
- ✅ **Pointage Model** - النموذج الرئيسي للحضور
- ✅ علاقات مع `EmploiTemps`, `Teacher`, `User`
- ✅ Scopes للفلترة (present, absent, byTeacher, byDate)
- ✅ Accessors للتنسيق وgetters للحالة

### 3. التحكم / Contrôleur
- ✅ **PointageController** كامل مع CRUD operations
- ✅ `index()` - عرض قائمة مع فلاتر متقدمة
- ✅ `create()` - إنشاء pointage جديد
- ✅ `store()` - حفظ مع validation
- ✅ `show()` - عرض تفاصيل مع إحصائيات
- ✅ `edit()` - تعديل pointage موجود  
- ✅ `update()` - تحديث مع validation
- ✅ `destroy()` - حذف pointage
- ✅ `rapide()` - واجهة الحضور السريع
- ✅ `storeRapide()` - حفظ حضور متعدد
- ✅ `getEmploisForTeacher()` - AJAX لجلب الدورات

### 4. الواجهات / Interfaces

#### أ) صفحة القائمة الرئيسية / Page d'Index
**الملف**: `resources/views/admin/pointages/index.blade.php`
- عرض جدول responsive للحضور
- فلاتر متقدمة (أستاذ، تاريخ، حالة)
- إحصائيات سريعة (حضور اليوم، الأسبوع، الشهر)
- أزرار إجراءات (إضافة، تعديل، عرض، حذف)
- دعم Bootstrap Table مع pagination

#### ب) صفحة الإضافة / Page de Création  
**الملف**: `resources/views/admin/pointages/create.blade.php`
- نموذج إنشاء pointage جديد
- اختيار الأستاذ ديناميكياً
- تحديد التاريخ مع date picker
- تحميل الدورات بـ AJAX حسب الأستاذ والتاريخ
- أزرار radio للحالة (حاضر/غائب)
- حقول اختيارية للملاحظات

#### ج) صفحة التعديل / Page d'Édition
**الملف**: `resources/views/admin/pointages/edit.blade.php`
- تعديل pointage موجود
- عرض المعلومات الحالية
- تحديث ديناميكي للدورات
- سجل التعديلات timeline
- validation شامل

#### د) صفحة العرض / Page de Détails
**الملف**: `resources/views/admin/pointages/show.blade.php`  
- عرض تفاصيل كاملة للـ pointage
- معلومات الأستاذ والدورة والحضور
- إحصائيات الأستاذ للشهر الحالي
- معلومات النظام (تاريخ الإنشاء والتعديل)
- أزرار إجراءات (تعديل، طباعة، حذف)
- modal تأكيد للحذف

#### هـ) الحضور السريع / Pointage Rapide
**الملف**: `resources/views/admin/pointages/rapide.blade.php`
- واجهة لتسجيل حضور جميع أساتذة اليوم
- عرض جميع الدورات المجدولة لليوم
- أزرار سريعة (الكل حاضر/غائب/إعادة تعيين)
- عداد الحضور الفوري
- تمييز الدورات المسجلة مسبقاً
- حفظ متعدد بـ transaction

### 5. الترجمة / Traduction

#### أ) الملفات العربية
**الملف**: `lang/ar/pointages.php`
- جميع النصوص مترجمة للعربية
- رسائل النجاح والخطأ
- تسميات الحقول والأزرار
- نصوص المساعدة

#### ب) الملفات الفرنسية  
**الملف**: `lang/fr/pointages.php`
- ترجمة كاملة للفرنسية
- تطابق مع النسخة العربية
- رسائل وتنبيهات مترجمة

### 6. التوجيه / Routing
**الملف**: `routes/web.php`
```php
// راوتات إدارة الحضور
Route::prefix('pointages')->name('pointages.')->group(function () {
    Route::get('/', [PointageController::class, 'index'])->name('index');
    Route::get('/create', [PointageController::class, 'create'])->name('create');
    Route::post('/', [PointageController::class, 'store'])->name('store');
    Route::get('/{pointage}', [PointageController::class, 'show'])->name('show');
    Route::get('/{pointage}/edit', [PointageController::class, 'edit'])->name('edit');
    Route::put('/{pointage}', [PointageController::class, 'update'])->name('update');
    Route::delete('/{pointage}', [PointageController::class, 'destroy'])->name('destroy');
    Route::get('/rapide/aujourd-hui', [PointageController::class, 'rapide'])->name('rapide');
    Route::post('/rapide/store', [PointageController::class, 'storeRapide'])->name('store-rapide');
    Route::get('/ajax/emplois', [PointageController::class, 'getEmploisForTeacher'])->name('get-emplois');
});
```

### 7. القائمة الجانبية / Sidebar
**الملف**: `resources/views/layouts/main-sidebar/sidebar.blade.php`
- قسم جديد "Gestion des Pointages"
- قائمة منسدلة مع الخيارات:
  - قائمة الحضور
  - إضافة حضور جديد  
  - الحضور السريع
- أيقونات Material Design

## طريقة الاستخدام / Mode d'Emploi

### 1. الوصول للنظام / Accès au Système
```
الرابط: /admin/pointages
المطلوب: تسجيل دخول كـ admin
```

### 2. الحضور اليومي / Pointage Quotidien
1. انتقل إلى "Pointage Rapide"
2. ستظهر جميع الدورات المجدولة لليوم
3. اختر حالة كل أستاذ (حاضر/غائب)
4. احفظ التغييرات

### 3. الحضور الفردي / Pointage Individuel  
1. انقر "Nouveau Pointage"
2. اختر الأستاذ
3. حدد التاريخ
4. اختر الدورة من القائمة المحملة
5. حدد الحالة والملاحظات
6. احفظ

### 4. التقارير والإحصائيات / Rapports et Statistiques
- عرض إحصائيات سريعة في الصفحة الرئيسية
- فلترة بالأستاذ والتاريخ والحالة  
- تفاصيل إضافية في صفحة العرض

## الملفات المضافة / Fichiers Ajoutés

### 1. قاعدة البيانات
- `database/migrations/2025_10_23_203744_create_pointages_table.php`

### 2. النماذج  
- `app/Models/Pointage.php`

### 3. المتحكمات
- `app/Http/Controllers/PointageController.php`

### 4. الواجهات
- `resources/views/admin/pointages/index.blade.php`
- `resources/views/admin/pointages/create.blade.php`
- `resources/views/admin/pointages/edit.blade.php`
- `resources/views/admin/pointages/show.blade.php`
- `resources/views/admin/pointages/rapide.blade.php`

### 5. الترجمات
- `lang/fr/pointages.php`
- `lang/ar/pointages.php`

### 6. الاختبارات
- `test_pointages_system.php`

## المتطلبات التقنية / Exigences Techniques

### Dependencies المطلوبة
- Laravel 11
- Bootstrap 5
- jQuery
- Select2  
- Material Design Icons
- Carbon (للتواريخ)

### Browser Support
- Chrome/Edge (latest)
- Firefox (latest)  
- Safari (latest)
- Internet Explorer 11+

## إعدادات إضافية / Configurations Supplémentaires

### 1. قواعد البيانات
```sql
-- تأكد من وجود الجداول المطلوبة
- emplois_temps 
- teachers
- classes
- subjects  
- horaires
- users
```

### 2. الأذونات / Permissions
```php
// في middleware أو policy
'pointages.create' => 'إنشاء حضور جديد'
'pointages.edit' => 'تعديل حضور موجود' 
'pointages.delete' => 'حذف حضور'
'pointages.view' => 'عرض الحضور'
```

## التطوير المستقبلي / Développement Futur

### مميزات مقترحة:
- [ ] تصدير التقارير PDF/Excel
- [ ] إشعارات للغياب المتكرر  
- [ ] تكامل مع نظام الحضور البيومتري
- [ ] تطبيق موبايل للأساتذة
- [ ] تقارير إحصائية متقدمة
- [ ] نظام إجازات وعطل
- [ ] تنبيهات تلقائية للإدارة

## الدعم / Support

للاستفسارات أو المشاكل التقنية:
- تحقق من log files في `storage/logs/`
- تأكد من تشغيل المهام المجدولة
- راجع إعدادات قاعدة البيانات

---

**تم إنشاء النظام بواسطة**: GitHub Copilot Assistant  
**التاريخ**: نوفمبر 2024  
**الإصدار**: 1.0  
**الترخيص**: MIT License
