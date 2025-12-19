<!-- partial:../../partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        {{-- dashboard --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('web.dashboard') }}">
                <span class="menu-title">{{ __('messages.dashboard') }}</span>
                <i class="mdi mdi-view-dashboard menu-icon"></i>
            </a>
        </li>

        {{-- الأساتذة --}}
        <li class="nav-item">
            <a href="{{ route('web.teachers.index') }}" class="nav-link">
                <span class="menu-title">Enseignants</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.niveauformations.index') }}" class="nav-link">
                <span class="menu-title">Formation</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.anneescolaires.index') }}" class="nav-link">
                <span class="menu-title">Annee Scolaire</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.salle-de-classes.index') }}" class="nav-link">
                <span class="menu-title">Salle de classes</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.departements.index') }}" class="nav-link">
                <span class="menu-title">Departement</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.specialities.index') }}" class="nav-link">
                <span class="menu-title">Specialites</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.niveau-pedagogiques.index') }}" class="nav-link">
                <span class="menu-title">Niveau pedagogique</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('web.trimesters.index') }}" class="nav-link">
                <span class="menu-title">Trimesters</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.classes.index') }}" class="nav-link">
                <span class="menu-title">Classes</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.students.index') }}" class="nav-link">
                <span class="menu-title">{{ __('messages.list_students') }}</span>
                <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.emplois.index') }}" class="nav-link">
                <span class="menu-title">Emploi du Temps</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>


        <li class="nav-item">
            <a href="{{ route('web.subjects.index') }}" class="nav-link">
                <span class="menu-title">Subject</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('web.subjects_teachers.index') }}" class="nav-link">
                <span class="menu-title">Affecter Prof</span>
                <i class="fa fa-chalkboard-teacher menu-icon"></i>
            </a>
        </li>

        {{-- Gestion des Pointages - Liste principale --}}
        <li class="nav-item">
            <a href="{{ route('web.pointages.index') }}" class="nav-link">
                <span class="menu-title">{{ __('pointages.gestion_pointages') }}</span>
                <i class="mdi mdi-clock-check menu-icon"></i>
            </a>
        </li>

        {{-- Pointage Rapide --}}
        <li class="nav-item">
            <a href="{{ route('web.pointages.rapide') }}" class="nav-link">
                <span class="menu-title">{{ __('pointages.pointage_rapide') }}</span>
                <i class="mdi mdi-clock-fast menu-icon"></i>
            </a>
        </li>

        {{-- Demandes des enseignants --}}
        <li class="nav-item">
            <a href="{{ route('web.teacher-requests.index') }}" class="nav-link">
                <span class="menu-title">Demandes enseignants</span>
                @php
                    $pendingRequestsCount = \App\Models\TeacherRequest::where('status', 'pending')->count();
                @endphp
                @if($pendingRequestsCount > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingRequestsCount }}</span>
                @endif
                <i class="mdi mdi-clipboard-list menu-icon"></i>
            </a>
        </li>

        {{-- الأقسام --}}
{{--        <li class="nav-item">--}}
{{--            <a href="" class="nav-link">--}}
{{--                <span class="menu-title">{{ __('sidebar.departments') }}</span>--}}
{{--                <i class="fa fa-building menu-icon"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>

</nav>
