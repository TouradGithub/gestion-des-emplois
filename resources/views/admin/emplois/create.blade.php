@extends('layouts.masters.master')

@section('title')
    {{ __('Créer un emploi du temps') }}
@endsection

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    .emploi-row {
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
        background: #f8f9fa;
    }

    .emploi-row:hover {
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .professor-info {
        font-size: 0.85em;
        color: #6c757d;
        font-style: italic;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .form-feedback {
        margin-top: 5px;
        font-size: 0.875em;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 4px;
        min-height: 38px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border: 1px solid #007bff;
        color: white;
        padding: 2px 8px;
        margin: 2px;
        border-radius: 3px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffc107;
    }
</style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Créer un emploi du temps</h3>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Information:</strong> Les professeurs appropriés pour la classe et le trimestre sélectionnés seront récupérés. Lors de la sélection d'un professeur, les matières qu'il enseigne dans cette spécialité s'afficheront.
                        </div>

                        <form class="pt-3" action="{{ route('web.emplois.store') }}" method="POST">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Classe <span class="text-danger">*</span></label>
                                    <select name="class_id" id="class_classe_id" class="form-control" required>
                                        <option value="">-- Choisir --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Trimestre <span class="text-danger">*</span></label>
                                    <select name="trimester_id" id="trimester_create_id" class="form-control" required>
                                        <option value="">-- Choisir --</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div id="emploi-rows">
                                <div class="emploi-row row align-items-end">
                                    <div class="form-group col-md-2">
                                        <label>Enseignant <span class="text-danger">*</span></label>
                                        <select name="teacher_id[]" class="form-control teacher-select" required>
                                            <option value="">-- Choisir --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Matière <span class="text-danger">*</span></label>
                                        <select name="subject_id[]" class="form-control subject-select" required>
                                            <option value="">-- Choisir --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Jour <span class="text-danger">*</span></label>
                                        <select name="jour_id[]" class="form-control" required>
                                            <option value="">-- Choisir --</option>
                                            @foreach($jours as $jour)
                                                <option value="{{ $jour->id }}">{{ $jour->libelle_fr }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                                                        <div class="form-group col-md-3">
                                        <label>Horaires <span class="text-danger">*</span></label>
                                        <select name="horaire_id[0][]" class="form-control horaire-select-multiple" multiple="multiple" required>
                                            @foreach($horaires as $horaire)
                                                <option value="{{ $horaire->id }}">{{ $horaire->libelle_fr }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Sélectionnez une ou plusieurs plages horaires</small>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Salle</label>
                                        <select name="salle_de_classe_id[]" class="form-control">
                                            <option value="">-- Choisir --</option>
                                            @foreach($salles as $salle)
                                                <option value="{{ $salle->id }}">{{ $salle->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <button type="button" class="btn btn-danger remove-row d-none">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-row" class="btn btn-success mt-3">Ajouter un autre cours</button>
                            <button type="submit" class="btn btn-theme mt-3">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // Charger les professeurs et matières pour chaque ligne
            function loadTeachersSubjects(row) {
                let classId = $('#class_classe_id').val();
                let trimesterId = $('#trimester_create_id').val();
                let teacherSelect = row.find('.teacher-select');
                let subjectSelect = row.find('.subject-select');

                if (classId && trimesterId) {
                    $.ajax({
                        url: baseUrl + '/admin/emplois/get-teachers',
                        type: 'GET',
                        data: { class_id: classId, trimester_id: trimesterId },
                        success: function (response) {
                            teacherSelect.empty().append('<option value="">-- Choisir un professeur --</option>');
                            if (response.data && response.data.length > 0) {
                                $.each(response.data, function (key, teacher) {
                                    let displayName = teacher.full_name || (teacher.nom + ' ' + teacher.prenom);
                                    let subjectsInfo = '';
                                    if (teacher.subjects && teacher.subjects.length > 0) {
                                        let subjectNames = teacher.subjects.map(s => s.name).join(', ');
                                        subjectsInfo = ` (${subjectNames})`;
                                    }
                                    teacherSelect.append(`<option value="${teacher.id}" data-subjects='${JSON.stringify(teacher.subjects || [])}'>${displayName}${subjectsInfo}</option>`);
                                });
                                teacherSelect.addClass('text-success');

                                // إضافة معلومات إضافية تحت القائمة
                                let infoDiv = teacherSelect.next('.form-feedback');
                                if (infoDiv.length === 0) {
                                    infoDiv = $('<div class="form-feedback text-success"></div>');
                                    teacherSelect.after(infoDiv);
                                }
                                infoDiv.html(`✅ ${response.data.length} professeur(s) disponible(s) pour ce trimestre`).removeClass('text-warning text-danger').addClass('text-success');
                            } else {
                                teacherSelect.append('<option value="">-- Aucun professeur disponible --</option>');
                                teacherSelect.addClass('text-warning');

                                let infoDiv = teacherSelect.next('.form-feedback');
                                if (infoDiv.length === 0) {
                                    infoDiv = $('<div class="form-feedback text-warning"></div>');
                                    teacherSelect.after(infoDiv);
                                }
                                infoDiv.html('⚠️ Aucun professeur assigné à ce trimestre').removeClass('text-success text-danger').addClass('text-warning');
                            }
                            subjectSelect.empty().append('<option value="">-- Choisir une matière --</option>');
                            subjectSelect.prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            console.log('Error loading teachers:', error);
                            teacherSelect.empty().append('<option value="">-- Erreur de chargement --</option>');
                            teacherSelect.addClass('text-danger');

                            let infoDiv = teacherSelect.next('.form-feedback');
                            if (infoDiv.length === 0) {
                                infoDiv = $('<div class="form-feedback text-danger"></div>');
                                teacherSelect.after(infoDiv);
                            }
                            infoDiv.html('❌ Erreur lors du chargement des professeurs').removeClass('text-success text-warning').addClass('text-danger');

                            subjectSelect.empty().append('<option value="">-- Choisir une matière --</option>');
                            subjectSelect.prop('disabled', false);
                        },
                        complete: function() {
                            teacherSelect.prop('disabled', false);
                        }
                    });
                } else {
                    teacherSelect.empty().append('<option value="">-- Choisir un professeur --</option>');
                    subjectSelect.empty().append('<option value="">-- Choisir une matière --</option>');
                    teacherSelect.removeClass('text-success text-warning text-danger');
                }
            }

            // تحميل المواد حسب الأستاذ في صف معين
            function loadSubjects(row, teacherId) {
                let subjectSelect = row.find('.subject-select');
                let classId = $('#class_classe_id').val();
                let trimesterId = $('#trimester_create_id').val();

                if (teacherId) {
                    // أولاً محاولة الحصول على المواد من بيانات الأستاذ المحفوظة
                    let teacherOption = row.find('.teacher-select option:selected');
                    let teacherSubjects = teacherOption.data('subjects');

                    if (teacherSubjects && teacherSubjects.length > 0) {
                        // استخدام المواد المحفوظة مع بيانات الأستاذ
                        subjectSelect.empty().append('<option value="">-- Choisir une matière --</option>');
                        $.each(teacherSubjects, function (key, subject) {
                            let displayName = subject.name;
                            if (subject.coefficient) {
                                displayName += ` (Coef: ${subject.coefficient})`;
                            }
                            subjectSelect.append(`<option value="${subject.id}">${displayName}</option>`);
                        });
                        subjectSelect.addClass('text-success');
                    } else {
                        // الاستدعاء التقليدي من الخادم
                        $.ajax({
                            url: baseUrl + '/admin/emplois/get-subjects',
                            type: 'GET',
                            data: {
                                teacher_id: teacherId,
                                class_id: classId,
                                trimester_id: trimesterId
                            },
                            success: function (response) {
                                subjectSelect.empty().append('<option value="">-- Choisir une matière --</option>');
                                if (response.subjects && response.subjects.length > 0) {
                                    $.each(response.subjects, function (key, subject) {
                                        let displayName = subject.name;
                                        if (subject.coefficient) {
                                            displayName += ` (Coef: ${subject.coefficient})`;
                                        }
                                        if (subject.specialite_name) {
                                            displayName += ` - ${subject.specialite_name}`;
                                        }
                                        subjectSelect.append(`<option value="${subject.id}">${displayName}</option>`);
                                    });
                                    subjectSelect.addClass('text-success');

                                    // إضافة معلومات تحت قائمة المواد
                                    let subjectInfoDiv = subjectSelect.next('.form-feedback');
                                    if (subjectInfoDiv.length === 0) {
                                        subjectInfoDiv = $('<div class="form-feedback text-success"></div>');
                                        subjectSelect.after(subjectInfoDiv);
                                    }
                                    subjectInfoDiv.html(`📚 ${response.subjects.length} matière(s) enseignée(s) par ce professeur`).removeClass('text-warning text-danger').addClass('text-success');
                                } else {
                                    subjectSelect.append('<option value="">-- Aucune matière disponible --</option>');
                                    subjectSelect.addClass('text-warning');

                                    let subjectInfoDiv = subjectSelect.next('.form-feedback');
                                    if (subjectInfoDiv.length === 0) {
                                        subjectInfoDiv = $('<div class="form-feedback text-warning"></div>');
                                        subjectSelect.after(subjectInfoDiv);
                                    }
                                    subjectInfoDiv.html('⚠️ Ce professeur n\'enseigne aucune matière dans ce trimestre').removeClass('text-success text-danger').addClass('text-warning');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log('Error loading subjects:', error);
                                subjectSelect.empty().append('<option value="">-- Erreur de chargement --</option>');
                                subjectSelect.addClass('text-danger');
                            }
                        });
                    }
                } else {
                    subjectSelect.empty().append('<option value="">-- Choisir une matière --</option>');
                    subjectSelect.removeClass('text-success text-warning text-danger');
                }
            }

                        // عند تغيير القسم، جلب الفصول الدراسية
            $('#class_classe_id').on('change', function () {
                let classId = $(this).val();
                let trimesterSelect = $('#trimester_create_id');

                trimesterSelect.empty().append('<option value="">-- Choisir --</option>');

                if (classId) {
                    // إضافة مؤشر التحميل
                    trimesterSelect.append('<option value="">🔄 Chargement des trimestres...</option>');
                    trimesterSelect.prop('disabled', true);

                    $.ajax({
                        url: baseUrl + '/admin/emplois/get-trimesters',
                        type: 'POST',
                        data: { class_id: classId },
                        success: function (response) {
                            trimesterSelect.empty().append('<option value="">-- Choisir un trimestre --</option>');
                            if (response.trimesters && response.trimesters.length > 0) {
                                $.each(response.trimesters, function (key, trimestre) {
                                    trimesterSelect.append(`<option value="${trimestre.id}">${trimestre.name}</option>`);
                                });
                                trimesterSelect.addClass('text-success');
                            } else {
                                trimesterSelect.append('<option value="">-- Aucun trimestre disponible --</option>');
                                trimesterSelect.addClass('text-warning');
                            }
                        },
                        error: function() {
                            console.log('Error loading trimesters');
                            trimesterSelect.empty().append('<option value="">-- Erreur de chargement --</option>');
                            trimesterSelect.addClass('text-danger');
                        },
                        complete: function() {
                            trimesterSelect.prop('disabled', false);
                        }
                    });
                } else {
                    trimesterSelect.removeClass('text-success text-warning text-danger');
                }

                // إفراغ الأساتذة والمواد في جميع الصفوف
                $('.emploi-row').each(function () {
                    let teacherSelect = $(this).find('.teacher-select');
                    let subjectSelect = $(this).find('.subject-select');
                    teacherSelect.empty().append('<option value="">-- Choisir un professeur --</option>');
                    subjectSelect.empty().append('<option value="">-- Choisir une matière --</option>');
                    teacherSelect.removeClass('text-success text-warning text-danger');
                    subjectSelect.removeClass('text-success text-warning text-danger');
                });
            });

            // عند تغيير السمستر، تحميل الأساتذة والمواد
            $('#trimester_create_id').on('change', function () {
                let trimesterId = $(this).val();
                if (trimesterId) {
                    // إضافة مؤشر تحميل لجميع صفوف الأساتذة
                    $('.emploi-row').each(function () {
                        let teacherSelect = $(this).find('.teacher-select');
                        let subjectSelect = $(this).find('.subject-select');
                        teacherSelect.empty().append('<option value="">🔄 Chargement des professeurs...</option>');
                        teacherSelect.prop('disabled', true);
                        subjectSelect.empty().append('<option value="">-- Attendez la sélection du professeur --</option>');
                        subjectSelect.prop('disabled', true);

                        loadTeachersSubjects($(this));
                    });
                } else {
                    $('.emploi-row').each(function () {
                        let teacherSelect = $(this).find('.teacher-select');
                        let subjectSelect = $(this).find('.subject-select');
                        teacherSelect.empty().append('<option value="">-- Choisir un professeur --</option>');
                        subjectSelect.empty().append('<option value="">-- Choisir une matière --</option>');
                        teacherSelect.removeClass('text-success text-warning text-danger').prop('disabled', false);
                        subjectSelect.removeClass('text-success text-warning text-danger').prop('disabled', false);
                    });
                }
            });

            // عند تغيير الأستاذ في أي صف
            $(document).on('change', '.teacher-select', function () {
                let row = $(this).closest('.emploi-row');
                let teacherId = $(this).val();
                loadSubjects(row, teacherId);
            });

            // تم نقل منطق إضافة الصف إلى الأسفل مع Select2            // حذف صف (يمنع حذف السطر الأول)
            $(document).on('click', '.remove-row', function () {
                if ($('.emploi-row').length > 1) {
                    $(this).closest('.emploi-row').remove();
                } else {
                    alert('Impossible de supprimer la première ligne !');
                }
            });

            // فاليديشن جافاسكريبت قبل الإرسال
            $('form').on('submit', function (e) {
                let valid = true;
                let errorMessages = [];

                // التحقق من القسم والسمستر
                if (!$('#class_classe_id').val()) {
                    $('#class_classe_id').addClass('is-invalid');
                    errorMessages.push('Veuillez choisir une classe');
                    valid = false;
                } else {
                    $('#class_classe_id').removeClass('is-invalid');
                }

                if (!$('#trimester_create_id').val()) {
                    $('#trimester_create_id').addClass('is-invalid');
                    errorMessages.push('Veuillez choisir un trimestre');
                    valid = false;
                } else {
                    $('#trimester_create_id').removeClass('is-invalid');
                }

                // التحقق من كل صف
                $('.emploi-row').each(function (index) {
                    let rowNumber = index + 1;
                    $(this).find('select[required], input[required]').each(function () {
                        if (!$(this).val()) {
                            $(this).addClass('is-invalid');
                            let fieldName = $(this).closest('.form-group').find('label').text().replace('*', '').trim();
                            errorMessages.push(`Ligne ${rowNumber}: ${fieldName} est requis`);
                            valid = false;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                    });
                });

                if (!valid) {
                    e.preventDefault();
                    let message = 'Erreurs de validation:\n' + errorMessages.join('\n');
                    alert(message);
                }
            });

            // تحميل الأساتذة والمواد للصف الأول عند التحميل - فقط إذا كان القسم والسمستر محددين
            if ($('#class_classe_id').val() && $('#trimester_create_id').val()) {
                loadTeachersSubjects($('.emploi-row').first());
            }
        });
    </script>
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
        .emploi-row {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }
        .emploi-row:first-child {
            background-color: #f8f9fa;
        }
        .remove-row {
            margin-top: 25px;
        }
    </style>

    <!-- Select2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2 للحصص الزمنية
            function initSelect2(container) {
                container.find('.horaire-select-multiple').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Sélectionnez les horaires...',
                    allowClear: true,
                    closeOnSelect: false,
                    width: '100%'
                });
            }

            // تهيئة Select2 للصف الأول
            initSelect2($('#emploi-rows'));

            // تحديث منطق إضافة صف جديد
            $('#add-row').on('click', function () {
                let lastRow = $('.emploi-row').last();
                let valid = true;

                // فحص الحقول المطلوبة
                lastRow.find('select[required]').each(function () {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        valid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // فحص إذا تم اختيار على الأقل حصة زمنية واحدة
                let horaireSelected = lastRow.find('.horaire-select-multiple').val();
                if (!horaireSelected || horaireSelected.length === 0) {
                    lastRow.find('.horaire-select-multiple').next('.select2-container').addClass('border-danger');
                    valid = false;
                } else {
                    lastRow.find('.horaire-select-multiple').next('.select2-container').removeClass('border-danger');
                }

                if (!valid) {
                    alert('Veuillez remplir tous les champs du dernier ligne et sélectionner au moins un horaire avant d\'ajouter une nouvelle séance !');
                    return;
                }

                let firstRow = $('.emploi-row').first();
                let newRow = firstRow.clone();
                let rowIndex = $('.emploi-row').length;

                // إعادة تعيين الحقول
                newRow.find('select').val('');
                newRow.find('.remove-row').removeClass('d-none');

                // تحديث أسماء الحقول
                newRow.find('.horaire-select-multiple').attr('name', `horaire_id[${rowIndex}][]`);

                // تدمير Select2 القديم وإنشاء جديد
                newRow.find('.select2-container').remove();
                newRow.find('.horaire-select-multiple').show();

                $('#emploi-rows').append(newRow);

                // تهيئة Select2 للصف الجديد
                initSelect2(newRow);

                loadTeachersSubjects(newRow);
            });

            // باقي الكود الموجود...
        });
    </script>
@endsection

