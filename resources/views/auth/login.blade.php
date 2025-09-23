<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Admin') }} || {{ config('app.name') }}</title>
    @include('layouts.include')

</head>

<body style="direction: ltr">
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">

                    <div class="auth-form-light text-left p-5">

                        <div class="brand-logo text-center">
                            Gestion des emplois du temps
{{--                            <img src="{{ env('LOGO2') ? url(Storage::url(env('LOGO2'))) :url('assets/logo.svg') }}" alt="logo">--}}
                        </div>
                        <form action="{{ url('login') }}"  method="POST" class="pt-3">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('email') }}</label>
                                <input id="email" type="email" class="form-control form-control-lg" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('email') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('password') }}</label>
                                <div class="input-group">
                                    <input id="password" type="password" class="form-control form-control-lg" name="password" required autocomplete="current-password" placeholder="{{ __('password') }}">
                                    <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-eye-slash" id="togglePassword"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="my-2 d-flex justify-content-end align-items-center">

                                    <a class="auth-link text-black" href="{{ route('password.request') }}">
                                        {{ __('forgot_password') }}
                                    </a>
                                </div>
                            @endif
                            <div class="mt-3">
                                <input type="submit" name="btnlogin" id="login_btn" value="{{ __('login') }}" class="btn btn-block btn-theme btn-lg font-weight-medium auth-form-btn"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('/assets/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/assets/jquery-toast-plugin/jquery.toast.min.js') }}"></script>

</body>

@if (Session::has('error'))
    <script type='text/javascript'>
        $.toast({
            text: '{{ Session::get('error') }}',
            showHideTransition: 'slide',
            icon: 'error',
            loaderBg: '#f2a654',
            position: 'top-right'
        });
    </script>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script type='text/javascript'>
            $.toast({
                text: '{{ $error }}',
                showHideTransition: 'slide',
                icon: 'error',
                loaderBg: '#f2a654',
                position: 'top-right'
            });
        </script>
    @endforeach
@endif

</html>
