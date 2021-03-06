@extends('layouts.BaseApp', ['class' => 'off-canvas-sidebar', 'activePage' => 'login', 'title' => __('Affiliate
Program'),
'titlePage' => 'Login'])

@section('content')
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-9 ml-auto mr-auto mb-3 text-center">
            <h3>Welcome to Affiliate</h3>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">

            <form class="form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="card card-login card-hidden mb-3">
                    <div class="card-header card-header-primary text-center pt-4 pb-4">
                        <h4 class="card-title"><strong>{{ __('Login') }}</strong></h4>
                    </div>
                    <div class="card-body mt-3">
                        @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{Session::get('error')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        @if (session('regis-succ'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{session('regis-succ')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">email</i>
                                    </span>
                                </div>
                                <input type="email" name="email" class="form-control" placeholder="{{ __('Email...') }}"
                                    value="{{ old('email') }}" required>
                            </div>
                            @if ($errors->has('email'))
                            <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                            @endif
                        </div>
                        <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">lock_outline</i>
                                    </span>
                                </div>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="{{ __('Password...') }}" required>
                                <span class="form-check-sign" id="check">
                                    <i class="material-icons password-icon text-secondary" aria-hidden="true"
                                        id="icon-pass">remove_red_eye</i>
                                </span>
                            </div>
                            @if ($errors->has('password'))
                            <div id="password-error" class="error text-danger pl-3" for="password"
                                style="display: block;">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer justify-content-center">
                        <button type="submit" class="btn btn-primary btn-link btn-lg">{{ __('Log in') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#check').click(function () {
            input = '#password';
            icon = '#icon-pass';
            if ($(input).attr('type') == 'password') {
                $(input).prop('type', 'text');
                $(icon).removeClass('text-secondary')
                $(icon).addClass('text-info');
            } else {
                $(icon).removeClass('text-info');
                $(icon).addClass('text-secondary');
                $(input).prop('type', 'password');
            }
        });
    });

</script>
@endpush
