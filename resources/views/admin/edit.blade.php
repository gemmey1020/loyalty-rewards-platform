@extends('layouts.app')

@section('title', __('dashboard.panel'))

@section('content')
    <div class="no-bottom no-top zebra" id="content">
        <div id="top"></div>

        <section id="subheader" class="text-light">
            <div class="subheader-overlay"></div>
            <div class="subheader-content center-y relative text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center d-flex justify-content-center hover-underline responsive-heading">
                            {{ __('dashboard.panel') }}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>

        <section id="section-cars" class="bg-gray-100">
            <div class="container">
                <div class="row">
                    @include('admin.partials.sidebar')

                    <div class="col-lg-9">
                        @include('admin.partials.stats')

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card p-4 rounded-5 mb25">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4>{{ __('dashboard.modal.edit_admin.title') }}</h4>
                                <a href="{{ route('admins.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                                </a>
                            </div>

                            <form action="{{ route('admins.update', $admin->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center mb-4">
                                            @if ($admin->photo)
                                                <img src="{{ asset('profile_images/' . $admin->photo) }}" alt="{{ $admin->name }}"
                                                    id="avatarPreview" class="img-fluid rounded-circle"
                                                    style="width: 200px; height: 200px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('template/images/profile/1.jpg') }}"
                                                    alt="{{ $admin->name }}" id="avatarPreview"
                                                    class="img-fluid rounded-circle"
                                                    style="width: 200px; height: 200px; object-fit: cover;">
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label for="image" class="form-label">
                                                {{ __('dashboard.modal.edit_admin.image_upload') }}
                                            </label>
                                            <input type="file" name="image" id="image" class="form-control"
                                                accept="image/*">
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="name"
                                                class="form-label">{{ __('dashboard.modal.edit_admin.name') }}</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                value="{{ old('name', $admin->name) }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email"
                                                class="form-label">{{ __('dashboard.modal.edit_admin.email') }}</label>
                                            <input type="email" name="email" id="email" class="form-control"
                                                value="{{ old('email', $admin->email) }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone"
                                                class="form-label">{{ __('dashboard.modal.edit_admin.phone') }}</label>
                                            <input type="text" name="phone" id="phone" class="form-control"
                                                value="{{ old('phone', $admin->phone) }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">
                                                {{ __('dashboard.modal.edit_admin.password') }}
                                            </label>
                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="{{ __('dashboard.modal.edit_admin.password_placeholder') }}">
                                            <small class="form-text text-muted">
                                                {{ __('Leave blank to keep current password') }}
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">
                                                {{ __('dashboard.modal.edit_admin.confirm_password') }}
                                            </label>
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="form-control">
                                        </div>

                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" class="buttonsave btn-shine me-2">
                                                <span>{{ __('dashboard.modal.edit_admin.save') }}</span>
                                            </button>
                                            <a href="{{ route('admins.index') }}" class="btn btn-danger">
                                                {{ __('dashboard.modal.edit_admin.cancel') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('image');
            const preview = document.getElementById('avatarPreview');

            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                }
            });
        });
    </script>
@endsection
