@extends('layouts.app')

@section('title', __('auth.reset_password.title'))

@section('content')
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>
        <section id="section-hero" aria-label="section" class="jarallax">
            @if ($image && $image->image)
                <img src="{{ asset('banner/login/' . $image->image) }}" class="jarallax-img" alt="">
            @else
                <img src="{{ asset('template/images/background/2.jpg') }}" class="jarallax-img" alt="">
            @endif

            <div class="v-center">
                <div class="container">
                    <div class="row align-items-center log">
                        <div class="col-lg-4 offset-lg-4">
                            <div class="padding40 rounded-3 shadow-soft" data-bgcolor="#ffffff">
                                <h4>{{ __('auth.reset_password.title') }}</h4>
                                <div class="spacer-10"></div>

                                @foreach (['success', 'error'] as $msg)
                                    @if (session($msg))
                                        <div class="alert alert-{{ $msg == 'success' ? 'success' : 'danger' }}">
                                            {{ session($msg) }}
                                        </div>
                                    @endif
                                @endforeach

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('password.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="field-set position-relative">
                                        <input type="password" id="password" name="password" placeholder="{{ __('auth.reset_password.password') }}" class="form-control" required>
                                        <span class="icon-pass" style="position: absolute; top: 50%; inset-inline-end: 15px; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                            <i class="fa fa-eye-slash" id="togglePassword1"></i>
                                        </span>
                                    </div>

                                    <div class="spacer-10"></div>

                                    <div class="field-set position-relative">
                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ __('auth.reset_password.confirm_password') }}" class="form-control" required>
                                        <span class="icon-pass" style="position: absolute; top: 50%; inset-inline-end: 15px; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                            <i class="fa fa-eye-slash" id="togglePassword2"></i>
                                        </span>
                                    </div>

                                    <div class="spacer-10"></div>

                                    <button class="btn btn-primary btn-lg btn-block" type="submit">{{ __('auth.reset_password.title') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword1 = document.getElementById('togglePassword1');
            const togglePassword2 = document.getElementById('togglePassword2');
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');

            togglePassword1.addEventListener('click', function () {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });

            togglePassword2.addEventListener('click', function () {
                const type = confirmInput.type === 'password' ? 'text' : 'password';
                confirmInput.type = type;
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });
        });
    </script>
@endpush
