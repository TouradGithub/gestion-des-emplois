@extends('layouts.masters.master')

@section('title', __('messages.student_details') . ' - ' . $student->fullname)

@section('css')
<style>
    .student-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: #fff;
        margin-bottom: 25px;
    }
    .student-header h2 {
        font-size: 1.8rem;
        margin-bottom: 5px;
    }
    .student-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.3);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .info-badge {
        background: rgba(255,255,255,0.2);
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-right: 10px;
        margin-bottom: 10px;
        display: inline-block;
    }
    .info-card {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }
    .info-card .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-card .card-title i {
        color: #667eea;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        color: #666;
        font-weight: 500;
    }
    .info-value {
        color: #333;
        font-weight: 600;
    }
    .history-timeline {
        position: relative;
        padding-left: 30px;
    }
    .history-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
    }
    .history-item {
        position: relative;
        padding: 15px 20px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .history-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    .history-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 50%;
        transform: translateY(-50%);
        width: 12px;
        height: 12px;
        background: #667eea;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 3px #667eea;
    }
    .history-item.current::before {
        background: #28a745;
        box-shadow: 0 0 0 3px #28a745;
    }
    .history-item.current {
        background: #d4edda;
        border-left: 3px solid #28a745;
    }
    .history-year {
        font-weight: 700;
        color: #667eea;
        font-size: 1rem;
    }
    .history-item.current .history-year {
        color: #28a745;
    }
    .history-class {
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
        margin: 5px 0;
    }
    .history-details {
        color: #666;
        font-size: 0.85rem;
    }
    .current-badge {
        background: #28a745;
        color: #fff;
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        margin-left: 10px;
    }
    .empty-history {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }
    .empty-history i {
        font-size: 3rem;
        margin-bottom: 15px;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Student Header -->
    <div class="student-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-4">
                @php
                    $imageUrl = $student->image ? Storage::url($student->image) : asset('images/default-student.svg');
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $student->fullname }}" class="student-avatar">
                <div>
                    <h2><i class="mdi mdi-account-school"></i> {{ $student->fullname }}</h2>
                    <div class="mt-3">
                        <span class="info-badge"><i class="mdi mdi-card-account-details"></i> {{ $student->nni }}</span>
                        @if($student->classe)
                            <span class="info-badge"><i class="mdi mdi-school"></i> {{ $student->classe->nom }}</span>
                        @endif
                        @if($student->classe && $student->classe->annee)
                            <span class="info-badge"><i class="mdi mdi-calendar"></i> {{ $student->classe->annee->annee }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('web.students.edit', $student) }}" class="btn btn-light me-2">
                    <i class="mdi mdi-pencil"></i> Modifier
                </a>
                <a href="{{ route('web.students.index') }}" class="btn btn-outline-light">
                    <i class="mdi mdi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Student Information -->
        <div class="col-md-6">
            <div class="info-card">
                <h5 class="card-title"><i class="mdi mdi-account-details"></i> Informations personnelles</h5>

                <div class="info-row">
                    <span class="info-label"><i class="mdi mdi-card-account-details me-2"></i>NNI</span>
                    <span class="info-value">{{ $student->nni }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="mdi mdi-account me-2"></i>Nom complet</span>
                    <span class="info-value">{{ $student->fullname }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="mdi mdi-account-supervisor me-2"></i>Nom du parent</span>
                    <span class="info-value">{{ $student->parent_name }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="mdi mdi-phone me-2"></i>Telephone</span>
                    <span class="info-value">{{ $student->phone }}</span>
                </div>

                @if($student->user)
                <div class="info-row">
                    <span class="info-label"><i class="mdi mdi-email me-2"></i>Email</span>
                    <span class="info-value">{{ $student->user->email }}</span>
                </div>
                @endif
            </div>

            <!-- Current Class Info -->
            @if($student->classe)
            <div class="info-card">
                <h5 class="card-title"><i class="mdi mdi-school"></i> Classe actuelle</h5>

                <div class="info-row">
                    <span class="info-label"><i class="mdi mdi-account-group me-2"></i>Classe</span>
                    <span class="info-value">{{ $student->classe->nom }}</span>
                </div>

                @if($student->classe->niveau)
                <div class="info-row">
                    <span class="info-label"><i class="mdi mdi-signal-variant me-2"></i>Niveau</span>
                    <span class="info-value">{{ $student->classe->niveau->nom }}</span>
                </div>
                @endif

                @if($student->classe->specialite)
                <div class="info-row">
                    <span class="info-label"><i class="mdi mdi-book-open-variant me-2"></i>Specialite</span>
                    <span class="info-value">{{ $student->classe->specialite->name }}</span>
                </div>
                @endif

                @if($student->classe->annee)
                <div class="info-row">
                    <span class="info-label"><i class="mdi mdi-calendar me-2"></i>Annee scolaire</span>
                    <span class="info-value">{{ $student->classe->annee->annee }}</span>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Academic History -->
        <div class="col-md-6">
            <div class="info-card">
                <h5 class="card-title"><i class="mdi mdi-history"></i> Parcours academique</h5>

                @if($classHistory->count() > 0)
                    <div class="history-timeline">
                        @foreach($classHistory as $history)
                            @php
                                $isCurrent = $student->class_id == $history->classe_id &&
                                            $student->classe &&
                                            $student->classe->annee_id == $history->annee_id;
                            @endphp
                            <div class="history-item {{ $isCurrent ? 'current' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="history-year">
                                            <i class="mdi mdi-calendar-range"></i>
                                            {{ $history->annee->annee ?? '-' }}
                                            @if($isCurrent)
                                                <span class="current-badge">Actuel</span>
                                            @endif
                                        </div>
                                        <div class="history-class">
                                            <i class="mdi mdi-school"></i>
                                            {{ $history->classe->nom ?? '-' }}
                                        </div>
                                        <div class="history-details">
                                            @if($history->classe && $history->classe->niveau)
                                                <span class="me-3">
                                                    <i class="mdi mdi-signal-variant"></i>
                                                    {{ $history->classe->niveau->nom }}
                                                </span>
                                            @endif
                                            @if($history->classe && $history->classe->specialite)
                                                <span>
                                                    <i class="mdi mdi-book-open-variant"></i>
                                                    {{ $history->classe->specialite->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($history->classe)
                                        <a href="{{ route('web.classes.show', $history->classe->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Voir la classe">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-history">
                        <i class="mdi mdi-history"></i>
                        <p>Aucun historique de parcours disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
