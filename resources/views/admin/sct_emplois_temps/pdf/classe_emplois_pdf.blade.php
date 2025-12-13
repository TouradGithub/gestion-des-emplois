@php
use Carbon\Carbon;
    $printed_details= [];
    $today = Carbon::now();
    $startWeek = $today->startOfWeek()->format('Y-m-d');
    $endWeek = $today->endOfWeek()->format('Y-m-d');
    $numDays = count($uniqueJours);
    $cellWidth = $numDays > 0 ? (100 - 12) / $numDays : 14;
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
            table-layout: fixed;
        }

        .pdf-table th,
        .pdf-table td {
            border: 1px solid #706f6f;
            padding: 8px 6px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
            overflow: hidden;
            box-sizing: border-box;
        }

        .pdf-table th {
            background-color: #4a5568;
            color: #fff;
            font-weight: bold;
            font-size: 14px;
            height: 40px;
        }

        .pdf-table th:first-child {
            width: 10%;
        }

        .pdf-table td:first-child {
            background-color: #edf2f7;
            font-weight: bold;
            font-size: 13px;
        }

        .pdf-table tbody tr {
            height: 60px;
        }

        .pdf-table tbody td {
            height: 60px;
            min-height: 60px;
            max-height: 60px;
        }

        .cell-content {
            display: block;
            height: 100%;
            overflow: hidden;
        }

        .matiere-name {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 4px;
            line-height: 1.3;
            color: #1a202c;
        }

        .salle-name {
            font-size: 12px;
            color: #2d3748;
            margin-bottom: 3px;
        }

        .teacher-name {
            font-size: 11px;
            color: #4a5568;
        }

        .empty-cell {
            background-color: #fff;
            height: 60px;
            min-height: 60px;
        }

        .filled-cell {
            background-color: #f8f9fa;
        }

       </style>
   </head>
   <body>
    <div style="text-align: center;">
        <div style="margin-bottom: 20px">
            <b style="font-size: 18px;">
                Emploi du temps de la classe: {!! $classe->nom !!}
            </b>
        </div>
    </div>
    <table class="pdf-table">
        <thead>
        <tr>
            <th style="width: 12%;">Heures</th>
            @foreach($uniqueJours as $day)
                <th style="width: {{ $cellWidth }}%;">{!! $day?->libelle_fr !!}</th>
            @endforeach
        </tr>
        </thead>
        @php
            $detailExamAlreadyPrinted = [];
        @endphp
        <tbody>
        @foreach($calendarData as $time => $days)
            <tr>
                <td>{{ $time }}</td>
                @foreach($days as $value)
                    @if (is_array($value) && !in_array($value['id'], $printed_details))
                        @php
                            $subjectType = $value['emploi']?->subject?->subjectType;
                            $typeClass = '';
                            $typeName = '';
                            if ($subjectType) {
                                $typeClass = 'type-' . strtolower($subjectType->code);
                                $typeName = $subjectType->name;
                            }
                        @endphp
                        <td rowspan="{{ $value['rowspan'] }}" class="filled-cell">
                            <div class="cell-content">
                                <span class="matiere-name">{{ $value['matiere'] }}@if($typeName) ({{ $typeName }})@endif</span>
                                @if($value['emploi']?->salle)
                                    <br>
                                    <span class="salle-name">{{ $value['emploi']->salle->name }}</span>
                                @endif
                                @if($value['emploi']?->teacher)
                                    <br>
                                    <span class="teacher-name">{{ $value['emploi']->teacher->name }}</span>
                                @endif
                                @if(!$classe)
                                    <br>
                                    <span class="teacher-name">{{ $value['emploi']?->classe?->nom }}</span>
                                @endif
                            </div>
                        </td>
                        @php($printed_details[] = $value['id'])
                    @elseif($value === 1)
                        <td class="empty-cell"></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
</body>
</html>
