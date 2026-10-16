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
                    <div class="col-lg-3 mb30">
                        <div class="card p-4 rounded-5">
                            <div class="profile_avatar">
                                <div class="profile_img position-relative">
                                    @if (Auth::user()->photo)
                                        <img src="{{ asset('profile_images/' . Auth::user()->photo) }}" alt="">
                                    @else
                                        <img src="{{ asset('template/images/profile/1.jpg') }}" alt="">
                                    @endif

                                    <button type="button" class="butonIconEfit" data-bs-toggle="modal"
                                        data-bs-target="#editAdminModal" title="Edit Admin">
                                        <i class="fa fa-pencil editicon"></i>
                                    </button>

                                </div>
                                <div class="profile_name">
                                    <h4>
                                        {{ Auth::user()->name ?? 'Admin' }}
                                        <span class="profile_username text-gray">{{ Auth::user()->email ?? 'Admin' }}</span>
                                    </h4>
                                </div>
                            </div>
                            <div class="spacer-20"></div>
                            <ul class="menu-col">
                                <li><a class="active" href="{{ route('admin.dashboard') }}"><i
                                            class="fa fa-home "></i>{{ __('dashboard.sidebar.dashboard') }}</a></li>
                                <li><a href="{{ route('admins.invoices.index') }}" id="load-invoices"><i
                                            class="fa fa-file-text"></i>{{ __('dashboard.sidebar.all_invoices') }}</a></li>
                                @if (Auth::user()->role == App\Enums\UserRole::SUPER_ADMIN->value)
                                    <li>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                                            <i class="fa fa-calendar"></i>{{ __('dashboard.sidebar.add_admins') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#pointsModal">
                                            <i class="fa fa-plus-circle"></i> {{ __('dashboard.sidebar.change_value') }}
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST"
                                        style="display: flex; align-items: center; padding: 5px 10px !important;">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline  w-100 fs-6"
                                            style="text-decoration: none; color: #333333; text-align: justify !important; ">
                                            <i
                                                class="fa fa-sign-out me-2 text-danger"></i>{{ __('dashboard.sidebar.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row flex-column flex-sm-row justify-content-around align-items-center">

                            <!-- Users Card -->
                            <div class="col-lg-3 col-6 mb25 order-sm-1">
                                <a href="{{ url('/admin/dashboard') }}" class="text-decoration-none text-dark">
                                    <div class="card p-4 rounded-5 h-100 text-center">
                                        <div class="symbol mb40">
                                            <i class="fa id-color fa-2x fa-user"></i>
                                        </div>
                                        <span class="h1 mb0">{{ $usersCount }}</span>
                                        <span class="text-gray">{{ __('dashboard.stats.users') }}</span>
                                    </div>
                                </a>
                            </div>

                            @if (Auth::user()->role == App\Enums\UserRole::SUPER_ADMIN->value)
                                <!-- Admins Card -->
                                <div class="col-lg-3 col-6 mb25 order-sm-1">
                                    <a href="{{ url('/admins') }}" class="text-decoration-none text-dark">
                                        <div class="card p-4 rounded-5 h-100 text-center">
                                            <div class="symbol mb40">
                                                <i class="fa id-color fa-2x fa-user-secret"></i>
                                            </div>
                                            <span class="h1 mb0">{{ $adminsCount }}</span>
                                            <span class="text-gray">{{ __('dashboard.stats.admins') }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endif

                            <!-- Invoices Card -->
                            <div class="col-lg-3 col-6 mb25 order-sm-1">
                                <a href="{{ url('/admin/invoices') }}" class="text-decoration-none text-dark">
                                    <div class="card p-4 rounded-5 h-100 text-center">
                                        <div class="symbol mb40">
                                            <i class="fa id-color fa-2x fa-file-text"></i>
                                        </div>
                                        <span class="h1 mb0">{{ $invoicesCount ?? 0 }}</span>
                                        <span class="text-gray">{{ __('dashboard.stats.invoices') }}</span>
                                    </div>
                                </a>
                            </div>

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

                        <div class="card p-4 rounded-5 mb25">
                            <h4>{{ __('dashboard.table.admins') }}</h4>

                            <div class="table-responsive">
                                <table id="usersTable" class="table table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>{{ __('dashboard.table.id') }}</th>
                                            <th>{{ __('dashboard.table.user_name') }}</th>
                                            <th>{{ __('dashboard.table.email') }}</th>
                                            <th>{{ __('dashboard.table.phone') }}</th>
                                            @if (Auth::user()->role == App\Enums\UserRole::SUPER_ADMIN->value)
                                                <th>{{ __('dashboard.table.actions') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($admins as $admin)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $admin->name }}</td>
                                                <td>{{ $admin->email ?? 'N/A' }}</td>
                                                <td>{{ $admin->phone ?? 'N/A' }}</td>
                                                @if (Auth::user()->role == App\Enums\UserRole::SUPER_ADMIN->value)
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.show', $admin->id) }}"
                                                            class="btn btn-info btn-sm me-2">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.edit', $admin->id) }}"
                                                            class="btn btn-primary btn-sm me-2">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteAdminModal-{{ $admin->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Delete Admin Modals -->
                        @if (Auth::user()->role == App\Enums\UserRole::SUPER_ADMIN->value)
                            @foreach ($admins as $admin)
                                <div class="modal fade" id="deleteAdminModal-{{ $admin->id }}" tabindex="-1"
                                    aria-labelledby="deleteAdminModalLabel-{{ $admin->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.destroy', $admin->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-content rounded-4">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteAdminModalLabel-{{ $admin->id }}">
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
                                                    <button type="submit"
                                                        class="btn btn-danger">{{ __('dashboard.modal.delete_admin.delete') }}</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        {{ __('dashboard.modal.delete_admin.cancel') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif

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
                                                    class="form-label">{{ __('dashboard.modal.add_admin.email') }}</label>
                                                <input type="text" name="email" id="email" class="form-control"
                                                    required value="{{ old('email') }}"
                                                    placeholder="{{ __('dashboard.modal.add_admin.enter_email') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone"
                                                    class="form-label">{{ __('dashboard.modal.add_admin.phone') }}</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    required value="{{ old('phone') }}"
                                                    placeholder="{{ __('dashboard.modal.add_admin.enter_phone') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="password"
                                                    class="form-label">{{ __('dashboard.modal.add_admin.password') }}</label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control" required
                                                    placeholder="{{ __('dashboard.modal.add_admin.enter_password') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="password_confirmation"
                                                    class="form-label">{{ __('dashboard.modal.add_admin.confirm_password') }}</label>
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control" required
                                                    placeholder="{{ __('dashboard.modal.add_admin.enter_password') }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="buttonsave btn-shine">
                                                <span>{{ __('dashboard.modal.add_admin.save') }}</span>
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">{{ __('dashboard.modal.add_admin.cancel') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

{{--                        <!-- Points & Amount Modal -->--}}
{{--                        <div class="modal fade" id="pointsModal" tabindex="-1" aria-labelledby="pointsModalLabel"--}}
{{--                            aria-hidden="true">--}}
{{--                            <div class="modal-dialog">--}}
{{--                                <form action="{{ route('points.store') }}" method="POST">--}}
{{--                                    @csrf--}}
{{--                                    <div class="modal-content rounded-4">--}}
{{--                                        <div class="modal-header">--}}
{{--                                            <h5 class="modal-title" id="pointsModalLabel">--}}
{{--                                                {{ __('dashboard.modal.points.title') }}--}}
{{--                                            </h5>--}}
{{--                                            <button type="button" class="btn-close" data-bs-dismiss="modal"--}}
{{--                                                aria-label="{{ __('dashboard.modal.points.close') }}"></button>--}}
{{--                                        </div>--}}
{{--                                        <div class="modal-body">--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="amount"--}}
{{--                                                    class="form-label">{{ __('dashboard.modal.points.amount') }}</label>--}}
{{--                                                <input type="number" name="amount" id="amount" class="form-control"--}}
{{--                                                    step="0.01"--}}
{{--                                                    placeholder="{{ __('dashboard.modal.points.amount_placeholder') }}"--}}
{{--                                                    required>--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="points"--}}
{{--                                                    class="form-label">{{ __('dashboard.modal.points.points') }}</label>--}}
{{--                                                <input type="number" name="points" id="points" class="form-control"--}}
{{--                                                    placeholder="{{ __('dashboard.modal.points.points_placeholder') }}"--}}
{{--                                                    required>--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="cashback"--}}
{{--                                                    class="form-label">{{ __('dashboard.modal.points.cashback') }}</label>--}}
{{--                                                <input type="number" name="cashback" id="cashback"--}}
{{--                                                    class="form-control"--}}
{{--                                                    placeholder="{{ __('dashboard.modal.points.cashback_placeholder') }}"--}}
{{--                                                    required>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="modal-footer">--}}
{{--                                            <button type="submit" class="buttonsave btn-shine">--}}
{{--                                                <span>{{ __('dashboard.modal.points.save') }}</span>--}}
{{--                                            </button>--}}
{{--                                            <button type="button" class="btn btn-danger"--}}
{{--                                                data-bs-dismiss="modal">{{ __('dashboard.modal.points.cancel') }}</button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <!-- Edit User Modal -->--}}
{{--                        @foreach ($users as $user)--}}
{{--                            <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1"--}}
{{--                                aria-labelledby="editUserModalLabel-{{ $user->id }}" aria-hidden="true">--}}
{{--                                <div class="modal-dialog">--}}
{{--                                    <form action="{{ route('users.update', $user->id) }}" method="POST"--}}
{{--                                        enctype="multipart/form-data">--}}
{{--                                        @csrf--}}
{{--                                        @method('PUT')--}}
{{--                                        <div class="modal-content rounded-4">--}}
{{--                                            <div class="modal-header">--}}
{{--                                                <h5 class="modal-title" id="editUserModalLabel-{{ $user->id }}">--}}
{{--                                                    {{ __('dashboard.modal.edit_user.title') }}: {{ $user->name }}--}}
{{--                                                </h5>--}}
{{--                                                <button type="button" class="btn-close" data-bs-dismiss="modal"--}}
{{--                                                    aria-label="{{ __('dashboard.modal.edit_user.close') }}"></button>--}}
{{--                                            </div>--}}
{{--                                            <div class="modal-body">--}}
{{--                                                <div class="mb-3">--}}
{{--                                                    <label for="name-{{ $user->id }}" class="form-label">--}}
{{--                                                        {{ __('auth.name') }}--}}
{{--                                                    </label>--}}
{{--                                                    <input type="text" name="name" id="name-{{ $user->id }}"--}}
{{--                                                        class="form-control" value="{{ $user->name }}" required>--}}
{{--                                                </div>--}}

{{--                                                <div class="mb-3">--}}
{{--                                                    <label for="email-{{ $user->id }}" class="form-label">--}}
{{--                                                        {{ __('auth.email') }}--}}
{{--                                                    </label>--}}
{{--                                                    <input type="email" name="email" id="email-{{ $user->id }}"--}}
{{--                                                        class="form-control" value="{{ $user->email }}" readonly>--}}
{{--                                                </div>--}}

{{--                                                <div class="mb-3">--}}
{{--                                                    <label for="image" class="form-label">--}}
{{--                                                        {{ __('dashboard.modal.edit_user.change_image') }}--}}
{{--                                                    </label>--}}
{{--                                                    <input type="file" name="image" id="image"--}}
{{--                                                        class="form-control" accept="image/*">--}}
{{--                                                    <div class="mt-2 text-center">--}}
{{--                                                        <img id="avatarPreview"--}}
{{--                                                            src="{{ asset('template/images/profile/1.jpg') }}"--}}
{{--                                                            alt="{{ __('dashboard.modal.edit_user.image_preview_alt') }}"--}}
{{--                                                            class="img-thumbnail" style="max-width: 150px;">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="modal-footer">--}}
{{--                                                <button type="submit" class="buttonsave btn-shine">--}}
{{--                                                    <span>{{ __('dashboard.modal.edit_user.save') }} </span>--}}
{{--                                                </button>--}}
{{--                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">--}}
{{--                                                    {{ __('dashboard.modal.edit_user.cancel') }}</button>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}


{{--                        <!-- Delete User Modal -->--}}
{{--                        @foreach ($users as $user)--}}
{{--                            <!-- Delete User Modal -->--}}
{{--                            <div class="modal fade" id="deleteUserModal-{{ $user->id }}" tabindex="-1"--}}
{{--                                aria-labelledby="deleteUserModalLabel-{{ $user->id }}" aria-hidden="true">--}}
{{--                                <div class="modal-dialog">--}}
{{--                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">--}}
{{--                                        @csrf--}}
{{--                                        @method('DELETE')--}}
{{--                                        <div class="modal-content rounded-4">--}}
{{--                                            <div class="modal-header">--}}
{{--                                                <h5 class="modal-title" id="deleteUserModalLabel-{{ $user->id }}">--}}
{{--                                                    {{ __('dashboard.modal.user_details.delete') }}: {{ $user->name }}--}}
{{--                                                </h5>--}}
{{--                                                <button type="button" class="btn-close" data-bs-dismiss="modal"--}}
{{--                                                    aria-label="{{ __('dashboard.close') }}"></button>--}}
{{--                                            </div>--}}
{{--                                            <div class="modal-body">--}}
{{--                                                <p>{{ __('dashboard.modal.invoice.delete_confirm') }}</p>--}}
{{--                                            </div>--}}
{{--                                            <div class="modal-footer">--}}
{{--                                                <button type="submit"--}}
{{--                                                    class="btn btn-danger">{{ __('dashboard.modal.invoice.delete') }}</button>--}}
{{--                                                <button type="button" class="btn btn-secondary"--}}
{{--                                                    data-bs-dismiss="modal">{{ __('dashboard.modal.invoice.cancel') }}</button>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <!-- Add Invoice Modal -->--}}
{{--                            <div class="modal fade" id="addInvoiceModal-{{ $user->id }}" tabindex="-1"--}}
{{--                                aria-labelledby="addInvoiceModalLabel-{{ $user->id }}" aria-hidden="true">--}}
{{--                                <div class="modal-dialog">--}}
{{--                                    <form action="{{ route('invoices.store') }}" method="POST">--}}
{{--                                        @csrf--}}
{{--                                        <div class="modal-content rounded-4">--}}
{{--                                            <div class="modal-header">--}}
{{--                                                <h5 class="modal-title" id="addInvoiceModalLabel-{{ $user->id }}">--}}
{{--                                                    {{ __('dashboard.modal.user_details.add_invoice') }}:--}}
{{--                                                    {{ $user->name }}--}}
{{--                                                </h5>--}}
{{--                                                <button type="button" class="btn-close" data-bs-dismiss="modal"--}}
{{--                                                    aria-label="{{ __('dashboard.close') }}"></button>--}}
{{--                                            </div>--}}
{{--                                            <div class="modal-body">--}}
{{--                                                <input type="hidden" name="user_id" value="{{ $user->id }}">--}}
{{--                                                <div class="mb-3">--}}
{{--                                                    <label for="invoice_number-{{ $user->id }}" class="form-label">--}}
{{--                                                        {{ __('dashboard.modal.invoice.invoice_number') }}--}}
{{--                                                    </label>--}}
{{--                                                    <input type="text" name="invoice_number"--}}
{{--                                                        id="invoice_number-{{ $user->id }}" class="form-control"--}}
{{--                                                        placeholder="{{ __('dashboard.modal.invoice.invoice_number_placeholder') }}">--}}
{{--                                                </div>--}}
{{--                                                <div class="mb-3">--}}
{{--                                                    <label for="invoice_total-{{ $user->id }}" class="form-label">--}}
{{--                                                        {{ __('dashboard.modal.invoice.invoice_total') }}--}}
{{--                                                    </label>--}}
{{--                                                    <input type="number" name="invoice_total"--}}
{{--                                                        id="invoice_total-{{ $user->id }}" class="form-control"--}}
{{--                                                        step="0.01"--}}
{{--                                                        placeholder="{{ __('dashboard.modal.invoice.invoice_total_placeholder') }}"--}}
{{--                                                        required>--}}
{{--                                                </div>--}}
{{--                                                <div class="mb-3">--}}
{{--                                                    <label for="invoice-date-{{ $user->id }}" class="form-label">--}}
{{--                                                        {{ __('dashboard.modal.invoice.date') }}--}}
{{--                                                    </label>--}}
{{--                                                    <input type="date" name="invoice_date"--}}
{{--                                                        id="invoice-date-{{ $user->id }}" class="form-control"--}}
{{--                                                        required>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="modal-footer">--}}
{{--                                                <button type="submit" class="buttonsave btn-shine">--}}
{{--                                                    <span>{{ __('dashboard.modal.invoice.save') }}</span>--}}
{{--                                                </button>--}}
{{--                                                <button type="button" class="btn btn-danger"--}}
{{--                                                    data-bs-dismiss="modal">{{ __('dashboard.modal.invoice.cancel') }}</button>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}

{{--                        <!-- Edit Admin Modal -->--}}
{{--                        <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel"--}}
{{--                            aria-hidden="true">--}}
{{--                            <div class="modal-dialog">--}}
{{--                                <form action="{{ route('admins.update', Auth::user()->id) }}" method="POST"--}}
{{--                                    enctype="multipart/form-data">--}}
{{--                                    @csrf--}}
{{--                                    @method('PUT')--}}
{{--                                    <div class="modal-content rounded-4">--}}
{{--                                        <div class="modal-header">--}}
{{--                                            <h5 class="modal-title" id="editAdminModalLabel">--}}
{{--                                                {{ __('dashboard.modal.edit_admin.title') }}--}}
{{--                                            </h5>--}}
{{--                                            <button type="button" class="btn-close" data-bs-dismiss="modal"--}}
{{--                                                aria-label="{{ __('dashboard.modal.edit_admin.close') }}"></button>--}}
{{--                                        </div>--}}
{{--                                        <div class="modal-body">--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="name"--}}
{{--                                                    class="form-label">{{ __('dashboard.modal.edit_admin.name') }}</label>--}}
{{--                                                <input type="text" name="name" id="name" class="form-control"--}}
{{--                                                    value="{{ Auth::user()->name }}" required>--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="email"--}}
{{--                                                    class="form-label">{{ __('dashboard.modal.edit_admin.email') }}</label>--}}
{{--                                                <input type="email" name="email" id="email" class="form-control"--}}
{{--                                                    value="{{ Auth::user()->email }}"--}}
{{--                                                    placeholder="{{ __('dashboard.modal.edit_admin.email_placeholder') }}"--}}
{{--                                                    required>--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="password"--}}
{{--                                                    class="form-label">{{ __('dashboard.modal.edit_admin.password') }}</label>--}}
{{--                                                <input type="password" name="password" id="password"--}}
{{--                                                    class="form-control"--}}
{{--                                                    placeholder="{{ __('dashboard.modal.edit_admin.password_placeholder') }}">--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="password_confirmation" class="form-label">--}}
{{--                                                    {{ __('dashboard.modal.edit_admin.confirm_password') }}--}}
{{--                                                </label>--}}
{{--                                                <input type="password" name="password_confirmation"--}}
{{--                                                    id="password_confirmation" class="form-control"--}}
{{--                                                    placeholder="{{ __('dashboard.modal.edit_admin.password_placeholder') }}">--}}
{{--                                            </div>--}}
{{--                                            <div class="mb-3">--}}
{{--                                                <label for="phone"--}}
{{--                                                    class="form-label">{{ __('dashboard.modal.edit_admin.phone') }}</label>--}}
{{--                                                <input type="text" name="phone" id="phone" class="form-control"--}}
{{--                                                    value="{{ Auth::user()->phone }}"--}}
{{--                                                    placeholder="{{ __('dashboard.modal.edit_admin.phone_placeholder') }}"--}}
{{--                                                    required>--}}
{{--                                            </div>--}}

{{--                                            <div class="mb-3">--}}
{{--                                                <label for="image" class="form-label">--}}
{{--                                                    {{ __('dashboard.modal.edit_admin.image_upload') }}--}}
{{--                                                </label>--}}
{{--                                                <input type="file" name="image" id="image"--}}
{{--                                                    class="form-control">--}}
{{--                                            </div>--}}

{{--                                            <div class="mb-3">--}}
{{--                                                <label for="avatarPreview"--}}
{{--                                                    class="form-label">{{ __('dashboard.modal.edit_admin.image_preview') }}</label>--}}

{{--                                                @if (Auth::user()->image)--}}
{{--                                                    <img id="avatarPreview" src="{{ Auth::user()->image }}"--}}
{{--                                                        alt="{{ __('dashboard.modal.edit_admin.image_preview_alt') }}"--}}
{{--                                                        class="img-thumbnail" style="max-width: 150px;">--}}
{{--                                                @else--}}
{{--                                                    <img id="avatarPreview"--}}
{{--                                                        src="{{ asset('template/images/profile/1.jpg') }}"--}}
{{--                                                        alt="{{ __('dashboard.modal.edit_admin.image_preview_alt') }}"--}}
{{--                                                        class="img-thumbnail" style="max-width: 150px;">--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="modal-footer">--}}
{{--                                            <button type="submit"--}}
{{--                                                class="buttonsave btn-shine">{{ __('dashboard.modal.edit_admin.save') }}</button>--}}
{{--                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">--}}
{{--                                                {{ __('dashboard.modal.edit_admin.cancel') }}--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- content close -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('image');
            const preview = document.getElementById('avatarPreview');

            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // URL.createObjectURL للمعاينة المؤقتة
                    preview.src = URL.createObjectURL(file);
                } else {
                    // لو شال الاختيار، نرجع للصورة الافتراضية
                    preview.src = "{{ asset('template/images/profile/1.jpg') }}";
                }
            });
        });
    </script>
@endsection
