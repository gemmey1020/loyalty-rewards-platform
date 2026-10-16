@extends('layouts.app')

@section('title', __('auth.login_title'))

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
                                <h4>{{ __('auth.login_title') }}</h4>
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

                                <form id="form_register" class="form-border" method="POST"
                                    action="{{ route('login.post') }}">
                                    @csrf
                                    <div class="field-set">
                                        <input type="text" name="email_or_phone" id="email_or_phone" class="form-control"
                                            placeholder="{{ __('auth.placeholder_email_or_phone') }}" required />
                                    </div>

                                    <div class="field-set">
                                        <div class="divinput-pass">
                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="{{ __('auth.placeholder_password') }}" required />
                                            <span class="icon-pass">
                                                <i class="fa fa-eye-slash" id="togglePassword"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div id="submit">
                                        <input type="submit" value="{{ __('auth.sign_in') }}"
                                            class="btn-main btn-fullwidth rounded-3" />
                                    </div>
                                </form>

                                {{--  forgot password --}}
                                <div class="spacer-8"></div>
                                <a href="{{ route('get.password.forget') }}" style="font-size: 12px; text-decoration: underline; color: #002459">{{ __('auth.forgot_password.subtitle') }}</a>

                                <div class="title-line" style="margin-top: 5px">{{ __('auth.or_signup_prompt') }}</div>

                                <div class="row g-2">
                                    <div class="col-lg-12">
                                        <a href="{{ route('register') }}"
                                            class="btn-main btn-fullwidth rounded-3">{{ __('auth.create_account') }}</a>
                                    </div>
                                </div>
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
            const form = document.getElementById('form_register');
            const toggle = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (toggle) {
                toggle.addEventListener('click', function() {
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;
                    this.classList.toggle('fa-eye-slash');
                    this.classList.toggle('fa-eye');
                });
            }

            if (form) {
                form.addEventListener('submit', function(e) {
                    let errors = [];
                    const value = document.getElementById('email_or_phone').value.trim();
                    const password = passwordInput.value;
                    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
                    const kuwaitPhonePattern = /^(?:\+965|00965)?[569]\d{7}$/;

                    if (!value) {
                        errors.push("{{ __('auth.email_or_phone_required') }}");
                    } else if (!emailPattern.test(value) && !kuwaitPhonePattern.test(value)) {
                        errors.push("{{ __('auth.invalid_email_or_phone') }}");
                    }

                    if (!password) {
                        errors.push("{{ __('auth.password_required') }}");
                    }

                    if (errors.length > 0) {
                        e.preventDefault();
                        showLoginErrors(errors);
                    }
                });

                function showLoginErrors(errors) {
                    let alertBox = document.querySelector('.alert-danger');
                    if (!alertBox) {
                        alertBox = document.createElement('div');
                        alertBox.className = 'alert alert-danger mt-3';
                        form.parentNode.insertBefore(alertBox, form);
                    }

                    const ul = document.createElement('ul');
                    ul.innerHTML = '';
                    errors.forEach(msg => {
                        const li = document.createElement('li');
                        li.textContent = msg;
                        ul.appendChild(li);
                    });

                    alertBox.innerHTML = '';
                    alertBox.appendChild(ul);
                }
            }
        });
    </script>
@endpush
