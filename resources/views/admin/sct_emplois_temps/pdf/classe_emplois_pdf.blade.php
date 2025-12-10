@php
//{{--    use Carbon\Carbon;use Dcs\Scolarite\Models\AnneesScolaire;--}}
//{{--    $formateur = \Dcs\Scolarite\Models\PrPeFormateur::where('user_id',auth()->user()->id)->first();--}}
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

            /* .pdf-table th, */
            .pdf-table td {
            /* border: 1px solid #ddd; */
            border-bottom: 1px solid #706f6f;
            /* border-top: 1px solid #ddd; */
            padding: 8px;
            text-align: left;
            }

            .pdf-table th {
            background-color: #f8f9fa;
            }

            .pdf-table tbody tr:nth-child(even) {
            /* background-color: #f2f2f2; */
            }

            .pdf-table tbody tr:hover {
                background-color: #ddd;
            }

       </style>
   </head>
   <body>
    <div style="text-align: center;">

        <div style="margin-bottom: 15px">
            <b>

                Emploi du temps de la filiere:    {!! $classe->nom !!}

            </b>

  </div>
    </div>
    <table class="pdf-table">
        <thead>
        <tr>
            <th>Heurs</th>
            @foreach($uniqueJours as $day)
                <th>{!! $day?->libelle_fr !!}</th>
            @endforeach
        </tr>
        </thead>
        @php
            $detailExamAlreadyPrinted = [];
        @endphp
        <tbody>
        @foreach($calendarData as $time => $days)
            <tr>
                <td>
                    {{ $time }}
                </td>
                @foreach($days as $value)
                    @if (is_array($value) && !in_array($value['id'], $printed_details))
                        <td rowspan="{{ $value['rowspan'] }}" class=" text-center" style="background-color:#f8f8f8;text-align: center">
                                {{ $value['matiere'] }}
                                @if($value['emploi']?->teacher)
                                <br>
                                <strong>
                                    {{ $value['emploi']->teacher->name }}
                                </strong>
                                @endif
                                @if($value['emploi']?->salle)
                                <br>
                                <small>
                                    {{ $value['emploi']->salle->name }}
                                </small>
                                @endif
                                @if(!$classe)
                                <br>
                                <strong>
                                    {{ $value['emploi']?->classe?->nom}}
                                </strong>
                                @endif
                        </td>
                        @php($printed_details[] = $value['id'])

                    @elseif($value === 1)
                        <td></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
</body>
</html>
