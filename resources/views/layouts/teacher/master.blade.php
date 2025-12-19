<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') || {{ config('app.name') }} - {{ __('teacher.dashboard') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS (LTR) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/assets/fonts/font-awesome.min.css') }}" />

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .teacher-sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: calc(100vh - 56px);
            padding: 20px 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 5px 0;
        }

        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 0 15px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
        }

        .main-content {
            padding: 20px;
        }

        .welcome-card {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
        }

        .info-card {
            border-left: 4px solid #007bff;
        }

        /* LTR specific styles */
        .sidebar-menu a i {
            margin-right: 8px;
        }

        .text-start {
            text-align: left !important;
        }

        .stats-card {
            background: linear-gradient(135deg, #6f42c1 0%, #007bff 100%);
            color: white;
        }

        .department-card {
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .department-card:hover {
            border-color: #007bff;
            box-shadow: 0 4px 8px rgba(0,123,255,0.2);
        }

        .footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 50px;
        }
    </style>

    @yield('css')
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('teacher.dashboard') }}">
                <i class="mdi mdi-school"></i>
                {{ config('app.name') }} - {{ __('teacher.teacher_portal') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teacher.dashboard') }}">
                            <i class="mdi mdi-view-dashboard"></i>
                            {{ __('teacher.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teacher.pointages') }}">
                            <i class="mdi mdi-clock-check"></i>
                            {{ __('teacher.my_pointages') }}
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="mdi mdi-account-circle"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('teacher.profile') }}">
                                <i class="mdi mdi-account-edit"></i>
                                {{ __('teacher.profile') }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="mdi mdi-logout"></i>
                                        {{ __('messages.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 teacher-sidebar">
                <div class="text-center mb-4">
                    <h5 class="text-white">{{ __('teacher.welcome') }}</h5>
                    <p class="text-white-50">{{ auth()->user()->teacher->name ?? auth()->user()->name }}</p>
                </div>

                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                            <i class="mdi mdi-view-dashboard me-2"></i>
                            {{ __('teacher.dashboard') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.emploi-temps') }}" class="{{ request()->routeIs('teacher.emploi-temps') ? 'active' : '' }}">
                            <i class="mdi mdi-calendar-clock me-2"></i>
                            {{ __('teacher.my_schedule') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.departments') }}" class="{{ request()->routeIs('teacher.departments*') || request()->routeIs('teacher.schedule*') ? 'active' : '' }}">
                            <i class="mdi mdi-book-open-variant me-2"></i>
                            {{ __('teacher.my_subjects') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.pointages') }}" class="{{ request()->routeIs('teacher.pointages*') ? 'active' : '' }}">
                            <i class="mdi mdi-clock-check me-2"></i>
                            {{ __('teacher.my_pointages') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.requests.index') }}" class="{{ request()->routeIs('teacher.requests*') ? 'active' : '' }}">
                            <i class="mdi mdi-clipboard-plus me-2"></i>
                            Demandes de séances
                            @php
                                $pendingCount = \App\Models\TeacherRequest::where('teacher_id', auth()->user()->teacher->id ?? 0)
                                    ->where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="badge bg-warning ms-1">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.profile') }}" class="{{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
                            <i class="mdi mdi-account-edit me-2"></i>
                            {{ __('teacher.profile') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('teacher.dashboard') }}">
                                    <i class="mdi mdi-home"></i> {{ __('teacher.dashboard') }}
                                </a>
                            </li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>

                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-alert-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-alert-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">
                &copy; {{ date('Y') }} {{ config('app.name') }} - {{ __('teacher.all_rights_reserved') }}
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var baseUrl = "{{ URL::to('/') }}";

        // Confirmation de suppression
        function confirmDelete(message) {
            return confirm(message || '{{ __("messages.confirm_delete") }}');
        }

        // Afficher le chargement
        function showLoading() {
            // Ajouter un spinner ici
        }

        function hideLoading() {
            // Masquer le spinner
        }
    </script>

    @yield('js')
    @yield('script')
</body>
</html>
