<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: #333;
        }
        .attestation-number {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #1a1a1a;
            background: #f5f5f5;
        }
        .content {
            margin: 20px 0;
            text-align: justify;
            padding: 0 15px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .date-place {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
            padding-right: 40px;
        }
        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #333;
            display: inline-block;
            width: 180px;
            text-align: center;
            padding-top: 5px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .stamp-area {
            float: left;
            text-align: center;
            margin-top: 40px;
        }
        .stamp-placeholder {
            width: 100px;
            height: 100px;
            border: 2px dashed #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    @include('globales.entete')

    <div class="attestation-number">
        <strong>N°:</strong> {{ $attestation->attestation_number }}<br>
        <strong>Date:</strong> {{ $attestation->approved_at->format('d/m/Y') }}
    </div>

    <div class="title">
        @if($attestation->type === 'travail')
            Attestation de Travail
        @elseif($attestation->type === 'salaire')
            Attestation de Salaire
        @else
            Attestation d'Expérience Professionnelle
        @endif
    </div>

    <div class="content">
        @if($attestation->type === 'travail')
            <p>
                Je soussigné(e), <strong>Directeur/Directrice de {{ config('app.name') }}</strong>, atteste par la présente que
                <strong>{{ $teacher->name }}</strong>@if($teacher->nni), titulaire du NNI <strong>{{ $teacher->nni }}</strong>@endif,
                est employé(e) au sein de notre établissement en qualité d'<strong>enseignant(e)</strong>
                depuis le début de son contrat jusqu'à ce jour.
            </p>
            <p>
                L'intéressé(e) fait preuve d'assiduité et de professionnalisme dans l'exercice de ses fonctions.
            </p>
        @elseif($attestation->type === 'salaire')
            <p>
                Je soussigné(e), <strong>Directeur/Directrice de {{ config('app.name') }}</strong>, atteste par la présente que
                <strong>{{ $teacher->name }}</strong>@if($teacher->nni), titulaire du NNI <strong>{{ $teacher->nni }}</strong>@endif,
                est employé(e) au sein de notre établissement en qualité d'<strong>enseignant(e)</strong>
                et perçoit une rémunération mensuelle conformément à son contrat de travail.
            </p>
        @else
            <p>
                Je soussigné(e), <strong>Directeur/Directrice de {{ config('app.name') }}</strong>, atteste par la présente que
                <strong>{{ $teacher->name }}</strong>@if($teacher->nni), titulaire du NNI <strong>{{ $teacher->nni }}</strong>@endif,
                a exercé les fonctions d'<strong>enseignant(e)</strong> au sein de notre établissement,
                où il/elle a dispensé des cours dans sa spécialité avec compétence et dévouement.
            </p>
            <p>
                Durant cette période, l'intéressé(e) a acquis une expérience significative
                dans le domaine de l'enseignement et de la formation professionnelle.
            </p>
        @endif

        <p>
            Cette attestation est délivrée à la demande de l'intéressé(e) pour servir et valoir ce que de droit.
        </p>
    </div>

    <div class="date-place">
        <p>Fait à Nouakchott, le {{ $attestation->approved_at->format('d/m/Y') }}</p>
    </div>

    <div style="overflow: hidden;">
        <div class="stamp-area">
            <p style="font-size: 10px; color: #999;">Cachet de l'établissement</p>
            <div class="stamp-placeholder">
                <span style="color: #ccc; font-size: 9px;">CACHET</span>
            </div>
        </div>

        <div class="signature">
            <p><strong>Le Directeur / La Directrice</strong></p>
            <div class="signature-line">
                Signature
            </div>
        </div>
    </div>

    <div class="footer">
        Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }} | {{ config('app.name') }}
    </div>
</body>
</html>
