<!-- partial:../../partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        {{-- dashboard --}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" href="">--}}
{{--                <span class="menu-title">{{ __('sidebar.dashboard') }}</span>--}}
{{--                <i class="fa fa-home menu-icon"></i>--}}
{{--            </a>--}}
{{--        </li>--}}

{{--        <li class="nav-item">--}}
{{--            <a href="" class="nav-link">--}}
{{--                <span class="menu-title">{{ __('sidebar.session_years') }}</span>--}}
{{--                <i class="fa fa-calendar-o menu-icon"></i>--}}
{{--            </a>--}}
{{--        </li>--}}

{{--        <li class="nav-item">--}}
{{--            <a href="" class="nav-link">--}}
{{--                <span class="menu-title">{{ __('sidebar.grades') }}</span>--}}
{{--                <i class="fa fa-graduation-cap menu-icon"></i>--}}
{{--            </a>--}}
{{--        </li>--}}

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

        {{-- الأقسام --}}
{{--        <li class="nav-item">--}}
{{--            <a href="" class="nav-link">--}}
{{--                <span class="menu-title">{{ __('sidebar.departments') }}</span>--}}
{{--                <i class="fa fa-building menu-icon"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>

</nav>
