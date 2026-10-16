@extends('layouts.app')

@section('title', __('auth.register_title'))

@section('content')
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <!-- section begin -->
        <section id="subheader" class="jarallax text-light">
            @if ($image && $image->image)
                <img src="{{ asset('banner/register/' . $image->image) }}" class="jarallax-img" alt="">
            @else
                <img src="{{ asset('template/images/background/subheader.jpg') }}" class="jarallax-img" alt="">
            @endif
            <div class="center-y relative text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1>{{ __('auth.register_title') }}</h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- section close -->

        <section aria-label="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <h3>{{ __('auth.register_subtitle') }}</h3>

                        <div class="spacer-10"></div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form name="contactForm" id='contact_form' class="form-border" method="post"
                            action='{{ route('register.post') }}'>
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-set">
                                        <label>{{ __('auth.name') }}</label>
                                        <input type='text' value="{{ old('name') }}" name='name' id='name'
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-set">
                                        <label>{{ __('auth.email') }}</label>
                                        <input type='text' value="{{ old('email') }}" name='email' id='email'
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-set">
                                        <label>{{ __('auth.dob') }}</label>
                                        <input type='date' value="{{ old('date') }}" name='date' id='date'
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-set">
                                        <label>{{ __('auth.phone') }}</label>
                                        <input type='text' value="{{ old('phone') }}" name='phone' id='phone'
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-set">
                                        <label>{{ __('auth.password') }}</label>
                                        <div class="divinput-pass">

                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="{{ __('auth.placeholder_password') }}" required />
                                            <span class="icon-pass">
                                                <i class="fa fa-eye-slash toggle-password" data-target="#password"></i>
                                            </span>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="field-set">
                                        <label>{{ __('auth.confirm_password') }}</label>
                                        <div class="divinput-pass">

                                            <input type="password" name='password_confirmation' id='re-password'
                                                class="form-control" placeholder="{{ __('auth.confirm_password') }}"
                                                required />
                                            <span class="icon-pass">
                                                <i class="fa fa-eye-slash toggle-password" data-target="#re-password"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div id='submit' class="pull-left">
                                        <input type='submit' id='send_message' value='{{ __('auth.register_button') }}'
                                            class="btn-main color-2">
                                    </div>

                                    <div id='mail_success' class='success'>{{ __('auth.success') }}</div>
                                    <div id='mail_fail' class='error'>{{ __('auth.fail') }}</div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contact_form');

            if (form) {
                form.addEventListener('submit', function(e) {
                    let valid = true;
                    let errors = [];

                    const name = form.querySelector('#name').value.trim();
                    const email = form.querySelector('#email').value.trim();
                    const date = form.querySelector('#date').value;
                    const phone = form.querySelector('#phone').value.trim();
                    const password = form.querySelector('#password').value;
                    const confirmPassword = form.querySelector('#re-password').value;

                    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
                    const phonePattern = /^[569]\d{7}$/;
                    const today = new Date().toISOString().split('T')[0];

                    if (!name) {
                        valid = false;
                        errors.push("{{ __('validation.required', ['attribute' => __('fields.name')]) }}");
                    }

                    if (!emailPattern.test(email)) {
                        valid = false;
                        errors.push("{{ __('validation.email', ['attribute' => __('fields.email')]) }}");
                    }

                    if (password.length < 8) {
                        valid = false;
                        errors.push(
                            "{{ __('validation.min.string', ['attribute' => __('fields.password'), 'min' => 8]) }}"
                        );
                    }

                    if (password !== confirmPassword) {
                        valid = false;
                        errors.push(
                            "{{ __('validation.confirmed', ['attribute' => __('fields.password')]) }}");
                    }

                    if (!phonePattern.test(phone)) {
                        valid = false;
                        errors.push("{{ __('validation.regex', ['attribute' => __('fields.phone')]) }}");
                    }

                    if (date && date >= today) {
                        valid = false;
                        errors.push(
                            "{{ __('validation.before', ['attribute' => __('fields.date'), 'date' => 'today']) }}"
                        );
                    }

                    if (!valid) {
                        e.preventDefault();
                        showErrors(form, errors);
                    }
                });
            }

            // Prevent non-numeric input in the phone field
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            function showErrors(form, errors) {
                const existingAlert = form.querySelector('.alert-danger');
                if (existingAlert) existingAlert.remove();

                const alert = document.createElement('div');
                alert.className = 'alert alert-danger mt-3';
                const ul = document.createElement('ul');

                errors.forEach(msg => {
                    const li = document.createElement('li');
                    li.textContent = msg;
                    ul.appendChild(li);
                });

                alert.appendChild(ul);
                form.prepend(alert);
            }

            const toggles = document.querySelectorAll('.toggle-password');
            toggles.forEach(function(icon) {
                icon.style.cursor = 'pointer';

                icon.addEventListener('click', function() {
                    const target = document.querySelector(icon.getAttribute('data-target'));
                    if (target) {
                        target.type = target.type === 'password' ? 'text' : 'password';
                        icon.classList.toggle('fa-eye-slash');
                        icon.classList.toggle('fa-eye');
                    }
                });
            });
        });
    </script>
@endpush
