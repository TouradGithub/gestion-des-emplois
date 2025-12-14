@php
use Carbon\Carbon;
// Récupérer l'année scolaire et le trimestre depuis le premier emploi
$annee = $emplois->first()?->classe?->annee;
$trimestre = $emplois->first()?->trimester;
@endphp
@extends('layout_pdf')
@section('page-content')
   @include('globales.entete_pointage')
   <!DOCTYPE html>
   <html lang="fr">
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #706f6f;
            margin-top: 15px;
        }

        .pdf-table th,
        .pdf-table td {
            border: 1px solid #706f6f;
            padding: 8px 6px;
            text-align: center;
            vertical-align: middle;
        }

        .pdf-table th {
            background-color: #4a5568;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
        }

        .pdf-table td {
            font-size: 10px;
        }

        .pdf-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .title-section {
            text-align: center;
            margin: 15px 0;
        }

        .title-section h2 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #1a202c;
        }

        .info-header {
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background-color: #f8f9fa;
        }

        .info-header table {
            width: 100%;
        }

        .info-header td {
            padding: 5px 10px;
            font-size: 12px;
        }

        .info-label {
            font-weight: bold;
            color: #4a5568;
        }

        .info-value {
            color: #1a202c;
        }

        .nb-col {
            width: 80px;
            min-height: 35px;
        }

        .footer-section {
          position: fixed;
        bottom: 10px;
        left: 0;
        right: 0;
        text-align: left;
        font-size: 10px;
        color: #666;
        padding-left: 20px;
        }

       </style>
   </head>
   <body>
    <div class="title-section">
        <h2>Annee Scolaire : {{ $annee->annee ?? 'Annee Scolaire' }}</h2>
    </div>

    <div class="info-header">
        <table>
            <tr>
                <td style="width: 33%;">
                    <span class="info-label">Trimestre:</span>
                    <span class="info-value">{{ $trimestre->name ?? '-' }}</span>
                </td>
                <td style="width: 34%;">
                    <span class="info-label">Jour:</span>
                    <span class="info-value">{{ $jour->libelle_fr ?? '' }}</span>
                </td>
                <td style="width: 33%; text-align: right;">
                    <span class="info-label">Date du jour:</span>
                    <span class="info-value">{{ $date->format('d-m-Y') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="pdf-table">
        <thead>
            <tr>
                <th style="width: 18%;">Profil</th>
                <th style="width: 12%;">Horaire</th>
                <th style="width: 15%;">Nom</th>
                <th style="width: 12%;">Tel</th>
                <th style="width: 12%;">Salle</th>
                <th style="width: 18%;">Matiere</th>
                <th class="nb-col">NB</th>
            </tr>
        </thead>
        <tbody>
            @forelse($emplois as $emploi)
                @php
                    $horaires = $emploi->ref_horaires()->orderBy('ordre')->get();
                    $horaireDisplay = '-';
                    if ($horaires->count() > 0) {
                        $firstHoraire = $horaires->first();
                        $lastHoraire = $horaires->last();
                        $startHour = \Carbon\Carbon::parse($firstHoraire->start_time)->format('G');
                        $endHour = \Carbon\Carbon::parse($lastHoraire->end_time)->format('G');
                        $horaireDisplay = $startHour . 'h-' . $endHour . 'h';
                    }
                @endphp
                <tr>
                    <td>{{ $emploi->classe->nom ?? '-' }}</td>
                    <td>{{ $horaireDisplay }}</td>
                    <td>{{ $emploi->teacher->name ?? '-' }}</td>
                    <td>{{ $emploi->teacher->phone ?? '-' }}</td>
                    <td>{{ $emploi->salle->name ?? '-' }}</td>
                    <td>{{ $emploi->subject->name ?? '-' }}</td>
                    <td class="nb-col"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Aucun cours pour cette journee
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>


@endsection
   </body>
   </html>
