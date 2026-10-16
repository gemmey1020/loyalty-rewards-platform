@extends('layouts.app')

@section('title', __('dashboard.panel'))

@section('content')
    <!-- content begin -->
    <div class="no-bottom no-top zebra" id="content">
        <div id="top"></div>

        <!-- section begin -->
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
        <!-- section close -->


        <!-- section begin -->
        <section id="section-cars" class="bg-gray-100">
            <div class="container">
                <div class="row">
                    @include('admin.partials.sidebar')

                    <div class="col-lg-9">
                        <div class="row flex-column flex-sm-row justify-content-around align-items-center">

                            <x-stat-card icon="fa-user" url="{{ url('/admin/dashboard') }}" :count="$usersCount"
                                :label="__('dashboard.stats.users')" />

                            <x-stat-card icon="fa-user-secret" url="{{ url('/admins') }}" :count="$adminsCount"
                                :label="__('dashboard.stats.admins')" />
                            <x-stat-card icon="fa-file-text" url="{{ url('/admin/invoices') }}" :count="$invoicesCount"
                                :label="__('dashboard.stats.invoices')" />
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- display the error message --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- display the validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card p-2 rounded-5 mb25">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>{{ __('dashboard.table.users') }}</h4>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createUserModal">
                                    <i class="fa fa-plus"></i> {{ __('dashboard.modal.user_details.create') }}
                                </button>
                            </div>

                            <!-- HTML Structure with Table Wrapper -->
                            <div class="table-responsive">
                                <table id="usersTable" class="table table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>{{ __('dashboard.table.id') }}</th>
                                            <th>{{ __('dashboard.table.user_name') }}</th>
                                            <th>{{ __('dashboard.table.email') }}</th>
                                            <th>{{ __('dashboard.table.phone') }}</th>
                                            <th>{{ __('dashboard.table.birth_date') }}</th>
                                            <th>{{ __('dashboard.modal.user_details.invoices') }}</th>
                                            <th>{{ __('dashboard.table.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email ?? 'N/A' }}</td>
                                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                                <td>{{ $user->birth_date ?? 'N/A' }}</td>
                                                <td>{{ $user->invoices->count() }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <!-- View User -->
                                                        <a href="{{ route('admins.users.show', $user->id) }}"
                                                            class="btn btn-primary btn-sm me-1 "
                                                            title="{{ __('dashboard.modal.user_details.title') }}">
                                                            <i class="fa fa-eye"></i> </a>

                                                        <!-- Edit User -->
                                                        <button type="button" class="btn btn-info btn-sm me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editUserModal-{{ $user->id }}"
                                                            title="{{ __('dashboard.modal.user_details.edit') }}">
                                                            <i class="fa fa-edit"></i>
                                                        </button>

                                                        <!-- Add Invoice -->
                                                        <button type="button" class="btn btn-warning btn-sm me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addInvoiceModal-{{ $user->id }}"
                                                            title="{{ __('dashboard.modal.user_details.add_invoice') }}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>

                                                        <!-- discount points -->
                                                        <button type="button" class="btn btn-success btn-sm me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#discountPointsModal-{{ $user->id }}"
                                                            title="{{ __('dashboard.modal.user_details.discount_points') }}">
                                                            <i class="fa fa-percent"></i>
                                                        </button>

                                                        <!-- Delete User -->
                                                        <button type="button" class="btn btn-danger btn-sm me-1 "
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteUserModal-{{ $user->id }}"
                                                            title="{{ __('dashboard.modal.user_details.delete') }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Create User Modal -->
                        <div class="modal fade" id="createUserModal" tabindex="-1"
                            aria-labelledby="createUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-content rounded-4">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createUserModalLabel">
                                                {{ __('dashboard.modal.user_details.create_title') }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="{{ __('dashboard.close') }}"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">{{ __('auth.name') }}</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">{{ __('auth.email') }}</label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone" class="form-label">{{ __('auth.phone') }}</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="password"
                                                    class="form-label">{{ __('auth.password') }}</label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="password_confirmation"
                                                    class="form-label">{{ __('auth.confirm_password') }}</label>
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="birth_date" class="form-label">{{ __('auth.dob') }}</label>
                                                <input type="date" name="birth_date" id="birth_date"
                                                    class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label for="image"
                                                    class="form-label">{{ __('dashboard.modal.edit_admin.image_upload') }}</label>
                                                <input type="file" name="image" id="image" class="form-control"
                                                    accept="image/*">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="buttonsave btn-shine">
                                                <span>{{ __('dashboard.modal.user_details.create') }}</span>
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">{{ __('dashboard.modal.edit_admin.cancel') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Edit User Modal -->
                        @foreach ($users as $user)
                            <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1"
                                aria-labelledby="editUserModalLabel-{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('users.update', $user->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class=" modal-content rounded-4">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUserModalLabel-{{ $user->id }}">
                                                    {{ __('dashboard.modal.edit_user.title') }}: {{ $user->name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="{{ __('dashboard.modal.edit_user.close') }}"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for=" name-{{ $user->id }}" class="form-label">
                                                        {{ __('auth.name') }}
                                                    </label>
                                                    <input type="text" name="name" id="name-{{ $user->id }}"
                                                        class="form-control" value="{{ $user->name }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email-{{ $user->id }}" class="form-label">
                                                        {{ __('auth.email') }}
                                                    </label>
                                                    <input type="email" name="email" id="email-{{ $user->id }}"
                                                        class="form-control" value="{{ $user->email }}" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email-{{ $user->id }}" class="form-label">
                                                        {{ __('auth.phone') }}
                                                    </label>
                                                    <input type="text" name="phone" id="phone-{{ $user->id }}"
                                                        class="form-control" value="{{ $user->phone }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="image" class="form-label">
                                                        {{ __('dashboard.modal.edit_user.change_image') }}
                                                    </label>
                                                    <input type="file" name="image" id="image"
                                                        class="form-control" accept="image/*">
                                                    <div class="mt-2 text-center">
                                                        <img id="avatarPreview"
                                                            src="{{ asset('template/images/profile/1.jpg') }}"
                                                            alt="{{ __('dashboard.modal.edit_user.image_preview_alt') }}"
                                                            class="img-thumbnail" style="max-width: 150px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="buttonsave btn-shine">
                                                    <span>{{ __('dashboard.modal.edit_user.save') }} </span>
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                                    {{ __('dashboard.modal.edit_user.cancel') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <!-- Add Admin Modal -->
                        <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('admins.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content rounded-4">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addAdminModalLabel">
                                                {{ __('dashboard.modal.add_admin.title') }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="{{ __('dashboard.modal.add_admin.close') }}"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="mb-3">
                                                <label for="name"
                                                    class="form-label">{{ __('dashboard.modal.add_admin.name') }}</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    required value="{{ old('name') }}"
                                                    placeholder="{{ __('dashboard.modal.add_admin.enter_name') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="email"
                                                    class=" form-label">{{ __('dashboard.modal.add_admin.email') }}</label>
                                                <input type="text" name="email" id="email" class="form-control"
                                                    value="{{ old('email') }}"
                                                    placeholder="{{ __('dashboard.modal.add_admin.enter_email') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone"
                                                    class=" form-label">{{ __('dashboard.modal.add_admin.phone') }}</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    value="{{ old('phone') }}"
                                                    placeholder="{{ __('dashboard.modal.add_admin.enter_phone') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="password"
                                                    class=" form-label">{{ __('dashboard.modal.add_admin.password') }}</label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control" required
                                                    placeholder="{{ __('dashboard.modal.add_admin.enter_password') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="password_confirmation"
                                                    class=" form-label">{{ __('dashboard.modal.add_admin.confirm_password') }}</label>
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control" required
                                                    placeholder="{{ __('dashboard.modal.add_admin.enter_password') }}">
                                            </div>
                                        </div>
                                        <div class=" modal-footer"> <button type="submit" class="buttonsave btn-shine">
                                                <span>{{ __('dashboard.modal.add_admin.save') }}</span>
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">{{ __('dashboard.modal.add_admin.cancel') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Points & Amount Modal -->
                        <div class="modal fade" id="pointsModal" tabindex="-1" aria-labelledby="pointsModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form id="pointsForm" action="{{ route('points.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-content rounded-4">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="pointsModalLabel">
                                                {{ __('dashboard.modal.points.title') }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="{{ __('dashboard.modal.points.close') }}"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="amount"
                                                    class="form-label">{{ __('dashboard.modal.points.amount') }}</label>
                                                <input type="number" name="amount" id="amount" class="form-control"
                                                    step="0.01"
                                                    placeholder="{{ __('dashboard.modal.points.amount_placeholder') }}"
                                                    required>
                                                <div id="amountError" class="error-message mt-1 text-danger small"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="points"
                                                    class="form-label">{{ __('dashboard.modal.points.points') }}</label>
                                                <input type="number" name="points" id="points" class="form-control"
                                                    placeholder="{{ __('dashboard.modal.points.points_placeholder') }}"
                                                    required>
                                                <div id="pointsError" class="error-message mt-1 text-danger small"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="cashback"
                                                    class="form-label">{{ __('dashboard.modal.points.cashback') }}</label>
                                                <input type="number" name="cashback" id="cashback"
                                                    class="form-control"
                                                    placeholder="{{ __('dashboard.modal.points.cashback_placeholder') }}"
                                                    required>
                                                <div id="cashbackError" class="error-message mt-1 text-danger small">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="buttonsave btn-shine">
                                                <span>{{ __('dashboard.modal.points.save') }}</span>
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">{{ __('dashboard.modal.points.cancel') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!-- Discount Points Modal -->
                        @foreach ($users as $user)
                            <div class="modal fade" id="discountPointsModal-{{ $user->id }}" tabindex="-1"
                                aria-labelledby="discountPointsModalLabel-{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('discountPoints.store', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content rounded-4">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="discountPointsModalLabel-{{ $user->id }}">
                                                    {{ __('dashboard.modal.discount_points.title') }} to:
                                                    {{ $user->name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="{{ __('dashboard.modal.discount_points.close') }}"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <input type="number" name="current_points"
                                                        id="current_points-{{ $user->id }}" class="form-control"
                                                        value="{{ $user->userPoints()->sum(DB::raw('points - points_deducted')) }}"
                                                        disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="number" name="points"
                                                        id="points-{{ $user->id }}" class="form-control"
                                                        placeholder="{{ __('dashboard.modal.discount_points.placeholder') }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="textarea" name="reason"
                                                        id="reason-{{ $user->id }}" class="form-control"
                                                        placeholder="{{ __('dashboard.modal.discount_points.reason') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="buttonsave btn-shine">
                                                    <span>{{ __('dashboard.modal.discount_points.save') }}</span>
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">{{ __('dashboard.modal.discount_points.cancel') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <!-- Delete User Modal -->
                        @foreach ($users as $user)
                            <!-- Delete User Modal -->
                            <div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1"
                                aria-labelledby="deleteUserModalLabel-{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content rounded-4">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteUserModalLabel-{{ $user->id }}">
                                                    {{ __('dashboard.modal.user_details.delete') }}:
                                                    {{ $user->name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="{{ __('dashboard.close') }}"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ __('dashboard.modal.invoice.delete_confirm') }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit"
                                                    class="btn btn-danger">{{ __('dashboard.modal.invoice.delete') }}</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">{{ __('dashboard.modal.invoice.cancel') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Add Invoice Modal -->
                            <div class="modal fade" id="addInvoiceModal-{{ $user->id }}" tabindex="-1"
                                aria-labelledby="addInvoiceModalLabel-{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('invoices.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-content rounded-4">
                                            <div class=" modal-header">
                                                <h5 class="modal-title" id="addInvoiceModalLabel-{{ $user->id }}">
                                                    {{ __('dashboard.modal.user_details.add_invoice') }}:
                                                    {{ $user->name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="{{ __('dashboard.close') }}"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="user_id" value="{{ $user->id }}"
                                                    aria-label="{{ __('dashboard.modal.invoice.user_id') }}">
                                                <div class="mb-3">
                                                    <label for=" invoice_number-{{ $user->id }}" class="form-label">
                                                        {{ __('dashboard.modal.invoice.invoice_number') }}
                                                    </label>
                                                    <input type="text" name="invoice_number"
                                                        id="invoice_number-{{ $user->id }}" class="form-control"
                                                        placeholder="{{ __('dashboard.modal.invoice.invoice_number_placeholder') }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="invoice_total-{{ $user->id }}" class="form-label">
                                                        {{ __('dashboard.modal.invoice.invoice_total') }}
                                                    </label>
                                                    <input type="number" name="invoice_total"
                                                        id="invoice_total-{{ $user->id }}" class="form-control"
                                                        step="0.01"
                                                        placeholder="{{ __('dashboard.modal.invoice.invoice_total_placeholder') }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="invoice-date-{{ $user->id }}" class="form-label">
                                                        {{ __('dashboard.modal.invoice.date') }}
                                                    </label>
                                                    <input type="date" name="invoice_date"
                                                        id="invoice-date-{{ $user->id }}" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="buttonsave btn-shine">
                                                    <span>{{ __('dashboard.modal.invoice.save') }}</span>
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">{{ __('dashboard.modal.invoice.cancel') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <!-- Edit Admin Modal -->
                        <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('admins.update', Auth::user()->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content rounded-4">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editAdminModalLabel">
                                                {{ __('dashboard.modal.edit_admin.title') }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="{{ __('dashboard.modal.edit_admin.close') }}"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name"
                                                    class="form-label">{{ __('dashboard.modal.edit_admin.name') }}</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    value="{{ Auth::user()->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email"
                                                    class="form-label">{{ __('dashboard.modal.edit_admin.email') }}</label>
                                                <input type=" email" name="email" id="email" class="form-control"
                                                    value="{{ Auth::user()->email }}"
                                                    placeholder="{{ __('dashboard.modal.edit_admin.email_placeholder') }}"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password"
                                                    class="form-label">{{ __('dashboard.modal.edit_admin.password') }}</label>
                                                <input type="password" name="password" id="password"
                                                    class=" form-control"
                                                    placeholder="{{ __('dashboard.modal.edit_admin.password_placeholder') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">
                                                    {{ __('dashboard.modal.edit_admin.confirm_password') }}
                                                </label>
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control"
                                                    placeholder="{{ __('dashboard.modal.edit_admin.password_placeholder') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone"
                                                    class="form-label">{{ __('dashboard.modal.edit_admin.phone') }}</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    value="{{ Auth::user()->phone }}"
                                                    placeholder=" {{ __('dashboard.modal.edit_admin.phone_placeholder') }}"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="image" class="form-label">
                                                    {{ __('dashboard.modal.edit_admin.image_upload') }}
                                                </label>
                                                <input type="file" name="image" id="image"
                                                    class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label for="avatarPreview"
                                                    class="form-label">{{ __('dashboard.modal.edit_admin.image_preview') }}</label>

                                                @if (Auth::user()->photo)
                                                    <div class="mt-2 text-center">
                                                        <img id="avatarPreview"
                                                            src="{{ asset('storage/' . Auth::user()->photo) }}"
                                                            alt="Image" class="img-thumbnail mt-2"
                                                            style="max-width: 150px;">
                                                    </div>
                                                @else
                                                    <div class="mt-2 text-center">
                                                        <img id="avatarPreview"
                                                            src="{{ asset('template/images/profile/1.jpg') }}"
                                                            alt="Image Preview" class="img-thumbnail mt-2"
                                                            style="max-width: 150px;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit"
                                                class="buttonsave btn-shine">{{ __('dashboard.modal.edit_admin.save') }}</button>
                                            <button type=" button" class="btn btn-danger" data-bs-dismiss="modal">
                                                {{ __('dashboard.modal.edit_admin.cancel') }}
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
    <!-- content close -->

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image Preview (already exists, so keep it)
            document.querySelectorAll('input[type="file"][name="image"]').forEach(input => {
                input.addEventListener('change', function() {
                    const previewId = this.closest('.modal').querySelector(
                        'img[id^="avatarPreview"]');
                    const file = this.files[0];
                    if (file && previewId) {
                        previewId.src = URL.createObjectURL(file);
                    } else if (previewId) {
                        previewId.src = "{{ asset('template/images/profile/1.jpg') }}";
                    }
                });
            });

            // Phone input digits only
            document.querySelectorAll('input[type="text"][name="phone"]').forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });

            // Edit Admin Modal frontend validation
            const editForm = document.querySelector('#editAdminModal form');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    const name = editForm.querySelector('input[name="name"]').value.trim();
                    const email = editForm.querySelector('input[name="email"]').value.trim();
                    const phoneInput = editForm.querySelector('input[name="phone"]');
                    const phone = phoneInput.value.trim();
                    const password = editForm.querySelector('input[name="password"]').value;
                    const confirmPassword = editForm.querySelector('input[name="password_confirmation"]')
                        .value;
                    const imageInput = editForm.querySelector('input[name="image"]');

                    let errors = [];

                    // Name validation
                    if (name.length === 0) {
                        errors.push(
                            "{{ __('validation.required', ['attribute' => __('dashboard.modal.edit_admin.name')]) }}"
                        );
                    }

                    // Email format
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(email)) {
                        errors.push(
                            "{{ __('validation.email', ['attribute' => __('dashboard.modal.edit_admin.email')]) }}"
                        );
                    }

                    // Phone regex
                    const phoneRegex = /^(?:\+965|00965)?[569]\d{7}$/;
                    if (!phoneRegex.test(phone)) {
                        errors.push(
                            "{{ __('validation.regex', ['attribute' => __('dashboard.modal.edit_admin.phone')]) }}"
                        );
                        phoneInput.classList.add('is-invalid');
                    } else {
                        phoneInput.classList.remove('is-invalid');
                    }

                    // Password min length and confirmation
                    if (password.length > 0 && password.length < 8) {
                        errors.push(
                            "{{ __('validation.min.string', ['attribute' => __('dashboard.modal.edit_admin.password'), 'min' => 8]) }}"
                        );
                    }
                    if (password !== confirmPassword) {
                        errors.push(
                            "{{ __('validation.confirmed', ['attribute' => __('dashboard.modal.edit_admin.password')]) }}"
                        );
                    }

                    // Image validation
                    if (imageInput.files.length > 0) {
                        const file = imageInput.files[0];
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                        if (!allowedTypes.includes(file.type)) {
                            errors.push(
                                "{{ __('validation.mimes', ['attribute' => __('dashboard.modal.edit_admin.image_upload'), 'values' => 'jpeg, png, jpg, gif']) }}"
                            );
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            errors.push(
                                "{{ __('validation.max.file', ['attribute' => __('dashboard.modal.edit_admin.image_upload'), 'max' => 2048]) }}"
                            );
                        }
                    }

                    // Remove previous alert
                    const existingAlert = editForm.querySelector('.alert-danger');
                    if (existingAlert) existingAlert.remove();

                    // Show errors if found
                    if (errors.length > 0) {
                        e.preventDefault();
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-danger mt-2';
                        const ul = document.createElement('ul');
                        errors.forEach(msg => {
                            const li = document.createElement('li');
                            li.textContent = msg;
                            ul.appendChild(li);
                        });
                        alert.appendChild(ul);
                        editForm.prepend(alert);
                    }
                });

            }

            // Add Admin Modal frontend validation
            const pointsForm = document.getElementById('pointsForm');
            if (pointsForm) {
                pointsForm.addEventListener('submit', function(e) {
                    let hasError = false;

                    const amountInput = document.getElementById('amount');
                    const pointsInput = document.getElementById('points');
                    const cashbackInput = document.getElementById('cashback');

                    const amount = parseFloat(amountInput.value);
                    const points = parseInt(pointsInput.value);
                    const cashback = parseFloat(cashbackInput.value);

                    // Clear previous error messages
                    document.getElementById('amountError').textContent = '';
                    document.getElementById('pointsError').textContent = '';
                    document.getElementById('cashbackError').textContent = '';

                    // Points: required, integer, >= 1
                    if (isNaN(points) || !Number.isInteger(points) || points < 1) {
                        document.getElementById('pointsError').textContent =
                            "{{ __('dashboard.validation.points_min') }}";
                        hasError = true;
                    }

                    // Amount: required, numeric, >= 0.01
                    if (isNaN(amount) || amount < 0.01) {
                        document.getElementById('amountError').textContent =
                            "{{ __('dashboard.validation.amount_positive') }}";
                        hasError = true;
                    }

                    // Cashback: required, numeric, >= 0.01
                    if (isNaN(cashback) || cashback < 0.01) {
                        document.getElementById('cashbackError').textContent =
                            "{{ __('dashboard.validation.cashback_positive') }}";
                        hasError = true;
                    }


                    if (hasError) {
                        e.preventDefault();
                    }
                });
            }

            // Add Admin Modal frontend validation
            const addAdminForm = document.querySelector('#addAdminModal form');
            if (addAdminForm) {
                addAdminForm.addEventListener('submit', function(e) {
                    // Clear previous errors
                    addAdminForm.querySelectorAll('.text-danger').forEach(el => el.remove());

                    const name = addAdminForm.querySelector('input[name="name"]').value.trim();
                    const email = addAdminForm.querySelector('input[name="email"]').value.trim();
                    const phone = addAdminForm.querySelector('input[name="phone"]').value.trim();
                    const password = addAdminForm.querySelector('input[name="password"]').value;
                    const confirmPassword = addAdminForm.querySelector(
                        'input[name="password_confirmation"]').value;

                    const phoneRegex = /^(?:\+965|00965)?[569]\d{7}$/;
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    let hasError = false;

                    function showError(input, message) {
                        const error = document.createElement('div');
                        error.className = 'text-danger mt-1 small';
                        error.textContent = message;
                        input.parentNode.appendChild(error);
                        hasError = true;
                    }

                    // Name
                    if (name.length === 0) {
                        showError(addAdminForm.querySelector('input[name="name"]'),
                            "{{ __('validation.required', ['attribute' => __('dashboard.modal.add_admin.name')]) }}"
                            );
                    }

                    // Email
                    if (!emailRegex.test(email)) {
                        showError(addAdminForm.querySelector('input[name="email"]'),
                            "{{ __('validation.email', ['attribute' => __('dashboard.modal.add_admin.email')]) }}"
                            );
                    }

                    // Phone
                    if (!phoneRegex.test(phone)) {
                        showError(addAdminForm.querySelector('input[name="phone"]'),
                            "{{ __('validation.regex', ['attribute' => __('dashboard.modal.add_admin.phone')]) }}"
                            );
                    }

                    // Password
                    if (password.length < 8) {
                        showError(addAdminForm.querySelector('input[name="password"]'),
                            "{{ __('validation.min.string', ['attribute' => __('dashboard.modal.add_admin.password'), 'min' => 8]) }}"
                            );
                    }

                    // Password confirmation
                    if (password !== confirmPassword) {
                        showError(addAdminForm.querySelector('input[name="password_confirmation"]'),
                            "{{ __('validation.confirmed', ['attribute' => __('dashboard.modal.add_admin.password')]) }}"
                            );
                    }

                    if (hasError) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
@endpush
