@php
use Carbon\Carbon;
    $printed_details= [];
    $today = Carbon::now();
    $startWeek = $today->startOfWeek()->format('Y-m-d');
    $endWeek = $today->endOfWeek()->format('Y-m-d');
@endphp
@extends('layout_pdf')
@section('page-content')
   @include('globales.entete')
   <!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <meta http-equiv="X-UA-Compatible" content="ie=edge">
       <style>
        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #706f6f;
            }

            .pdf-table td {
            border-bottom: 1px solid #706f6f;
            padding: 8px;
            text-align: left;
            }

            .pdf-table th {
            background-color: #f8f9fa;
            border-bottom: 1px solid #706f6f;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            }

            .pdf-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
            }

            .student-info {
                margin-bottom: 20px;
                padding: 10px;
                border: 1px solid #ddd;
                background-color: #f8f9fa;
            }

            .header-title {
                text-align: center;
                margin-bottom: 20px;
                font-size: 18px;
                font-weight: bold;
            }

            .time-cell {
                background-color: #e9ecef;
                font-weight: bold;
                text-align: center;
            }

            .subject-cell {
                text-align: center;
                background-color: #f8f8f8;
            }

       </style>
   </head>
   <body>

    <!-- Student Information -->
    <div class="student-info">
        <strong>معلومات الطالب - Student Information:</strong><br>
        <strong>الاسم الكامل - Full Name:</strong> {{ $student->fullname }}<br>
        <strong>رقم التعريف الوطني - NNI:</strong> {{ $student->nni }}<br>
        <strong>الصف - Class:</strong> {{ $student->classe->nom }}<br>
        @if($student->classe->niveau)
        <strong>المستوى - Level:</strong> {{ $student->classe->niveau->nom }}<br>
        @endif
        @if($student->classe->specialite)
        <strong>التخصص - Speciality:</strong> {{ $student->classe->specialite->nom }}<br>
        @endif
    </div>

    <div class="header-title">
        الجدول الزمني للطالب - Student Schedule<br>
        <small>{{ $student->classe->nom }}</small>
    </div>

    <table class="pdf-table">
        <thead>
        <tr>
            <th>الوقت - Time</th>
            @foreach($uniqueJours as $day)
                <th>{{ $day?->libelle_ar }} - {{ $day?->libelle_fr }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($calendarData as $time => $days)
            <tr>
                <td class="time-cell">
                    {{ $time }}
                </td>
                @foreach($days as $value)
                    @if (is_array($value) && !in_array($value['id'], $printed_details))
                        <td rowspan="{{ $value['rowspan'] }}" class="subject-cell">
                            <strong>{{ $value['matiere'] }}</strong>
                            <br>
                            <small>المدة: {{ $value['nbr_heure'] }}h</small>
                            <br>
                            @if(isset($value['emploi']->teacher))
                            <em>المعلم: {{ $value['emploi']->teacher->nom ?? 'غير محدد' }}</em>
                            <br>
                            @endif
                            @if(isset($value['emploi']->sct_salle))
                            <span>القاعة: {{ $value['emploi']->sct_salle->libelle_fr ?? 'غير محدد' }}</span>
                            @endif
                        </td>
                        @php
                            $printed_details[] = $value['id'];
                        @endphp
                    @elseif(!is_array($value))
                        <td>{{ $value }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 12px; color: #666; text-align: center;">
        تم طباعة هذا الجدول في تاريخ: {{ date('Y-m-d H:i:s') }}
    </div>

   </body>
   </html>
@endsection
