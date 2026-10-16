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

                        <div class="card p-4 rounded-5 mb25">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4>{{ __('dashboard.modal.user_details.title') }}</h4>
                                <a href="{{ route('admins.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                                </a>
                            </div>

                            <div class="row">
                                <div class="col-md-4 text-center mb-4">
                                    @if ($admin->photo)
                                        <img src="{{ asset('profile_images/' . $admin->photo) }}" alt="{{ $admin->name }}"
                                            class="img-fluid rounded-circle"
                                            style="width: 200px; height: 200px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('template/images/profile/1.jpg') }}" alt="{{ $admin->name }}"
                                            class="img-fluid rounded-circle"
                                            style="width: 200px; height: 200px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 30%;">{{ __('dashboard.modal.edit_admin.name') }}</th>
                                                    <td>{{ $admin->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('dashboard.modal.edit_admin.email') }}</th>
                                                    <td>{{ $admin->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('dashboard.modal.edit_admin.phone') }}</th>
                                                    <td>{{ $admin->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Created At') }}</th>
                                                    <td>{{ $admin->created_at->format('Y-m-d H:i:s') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-primary me-2">
                                            <i class="fa fa-pencil"></i> {{ __('dashboard.modal.edit_admin.title') }}
                                        </a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteAdminModal-{{ $admin->id }}">
                                            <i class="fa fa-trash"></i> {{ __('dashboard.modal.delete_admin.title') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Admin Modal -->
                        <div class="modal fade" id="deleteAdminModal-{{ $admin->id }}" tabindex="-1"
                            aria-labelledby="deleteAdminModalLabel-{{ $admin->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.destroy', $admin->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content rounded-4">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteAdminModalLabel-{{ $admin->id }}">
                                                {{ __('dashboard.modal.delete_admin.title') }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{ __('dashboard.modal.delete_admin.confirm', ['name' => $admin->name]) }}
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">
                                                {{ __('dashboard.modal.delete_admin.delete') }}
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                {{ __('dashboard.modal.delete_admin.cancel') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
