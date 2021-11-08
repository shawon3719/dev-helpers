@extends('diagnosticcentre::layouts.auth')
@section('content')
<div id="auth">
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{url('/my-reports')}}"><img src="{{asset( isset($settings['system_logo']) ? Storage::url($settings['system_logo']) : 'img/hms_logo.png') }}" alt="Logo"></a>
                </div>
                <h1 class="auth-title">Log in.</h1>
                <p class="auth-subtitle mb-3">Log in with your data, Bill-ID as your user-id and your phone number as password.</p>

                <form action="{{ route('my-reports.login') }}" method="POST">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-3">
                        <input id="user_id" type="user_id" class="form-control form-control-md {{ $errors->has('user_id') ? ' is-invalid' : '' }}" required autocomplete="user_id" autofocus placeholder="{{ trans('diagnosticcentre::global.login_user_id') }}" name="user_id" value="{{ old('user_id', null) }}">
                        <div class="form-control-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    @if($errors->has('user_id'))
                        <small class="text-danger">
                            {{ $errors->first('user_id') }}
                        </small>
                    @endif
                    <div class="form-group position-relative has-icon-left mb-3">
                        <input id="password" type="password" class="form-control form-control-md {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ trans('diagnosticcentre::global.login_password') }}">
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    @if($errors->has('password'))
                        <small class="text-danger">
                            {{ $errors->first('password') }}
                        </small>
                    @endif
                    <div class="form-check form-check-lg d-flex align-items-end">
                        <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label text-gray-600" for="flexCheckDefault">
                            Keep me logged in
                        </label>
                    </div>
                    <button class="btn btn-primary btn-block btn-md shadow-md mt-3">Log in</button>
                </form>
                @if(Route::has('register'))
                    <div class="text-center mt-3 text-sm">
                        <p class="text-gray-600">Don't have an account? <a href="{{route('register')}}"
                                class="font-bold">Sign
                                up</a>.</p>
                        <p><a class="font-bold" href="{{route('password.request')}}">Forgot password?</a>.</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right-guest">

            </div>
        </div>
    </div>

</div>
@endsection
