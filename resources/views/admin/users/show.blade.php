@extends('layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="no-bottom no-top zebra" id="content">
        <div id="top"></div>

        <!-- Subheader Section -->
        <section id="subheader" class="jarallax text-light">
            <img src="{{ asset('template/images/background/14.jpg') }}" class="jarallax-img" alt="">
            <div class="center-y relative text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1>{{ __('dashboard.hi') }}, {{ $user->name }}</h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Section -->
        <section id="section-user" class="bg-gray-100">
            <div class="container">
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-lg-3 mb30">
                        <div class="card p-4 rounded-5">
                            <div class="profile_avatar">
                                <div class="profile_img position-relative">
                                    @if (Auth::user()->photo)
                                        <img src="{{ asset('profile_images/' . Auth::user()->photo) }}" alt="">
                                    @else
                                        <img src="{{ asset('template/images/profile/1.jpg') }}" alt="">
                                    @endif
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
                                            class="fa fa-home"></i>{{ __('dashboard.sidebar.dashboard') }}</a></li>
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

                    <!-- User Details -->
                    <div class="col-lg-9">
                        <div class="card p-4 rounded-5 mb25">
                            <h4>{{ __('dashboard.modal.user_details.title') }}</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>{{ __('dashboard.modal.user_details.name') }}:</strong> {{ $user->name }}
                                    </p>
                                    <p><strong>{{ __('dashboard.modal.user_details.email') }}:</strong>
                                        {{ $user->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>{{ __('dashboard.modal.user_details.invoices') }}:</strong>
                                        {{ $user->invoices->count() }}</p>

                                    <p><strong>{{ __('dashboard.modal.user_details.total_cashback') }}:</strong>
                                        {{ $user->userPoints->sum('cashback') ?? 0 }}</p>
                                    <p><strong>{{ __('dashboard.modal.user_details.total_cashback_after_points_deducted') }}:</strong>
                                        {{ $user->userPoints->sum('net_cashback') ?? 0 }}</p>

                                    <p><strong>{{ __('dashboard.modal.user_details.total_points') }}:</strong>
                                        {{ $user->userPoints->sum('points') ?? 0 }}</p>
                                    <p><strong>{{ __('dashboard.modal.user_details.total_points_after_points_deducted') }}:</strong>
                                        {{ $user->userPoints()->sum(DB::raw('points - points_deducted')) ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-2 flex-column flex-sm-row align-items-center">
                                <button type="button" class="btn btn-info btn-sm me-1" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal" title="Edit User">
                                    <i class="fa fa-edit"></i> {{ __('dashboard.modal.user_details.edit') }}
                                </button>
                                <button type="button" class="btn btn-danger btn-sm me-1 mt-1" data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal" title="Delete User">
                                    <i class="fa fa-trash"></i> {{ __('dashboard.modal.user_details.delete') }}
                                </button>
                                <button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal"
                                    data-bs-target="#addInvoiceModal-{{ $user->id }}" title="Add Invoice">
                                    <i class="fa fa-plus"></i> {{ __('dashboard.modal.user_details.add_invoice') }}
                                </button>
                                {{-- <a href="{{ route('admins.users.deduction-history', $user->id) }}"
                                    class="btn btn-primary btn-sm" target="_blank">
                                    <i class="fa fa-history"></i>
                                    {{ __('dashboard.modal.user_details.view_deduction_history') }}
                                </a> --}}
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

                        <!-- Invoices Table -->
                        <div class="card p-4 rounded-5 mb25">
                            <ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="invoices-tab" data-bs-toggle="tab"
                                        data-bs-target="#invoices" type="button" role="tab" aria-controls="invoices"
                                        aria-selected="true">
                                        {{ __('dashboard.recent_invoices') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="deductions-tab" data-bs-toggle="tab"
                                        data-bs-target="#deductions" type="button" role="tab"
                                        aria-controls="deductions" aria-selected="false">
                                        {{ __('dashboard.deduction_history.title') }}
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="dashboardTabsContent">
                                <!-- Invoices Tab -->
                                <div class="tab-pane fade show active" id="invoices" role="tabpanel"
                                    aria-labelledby="invoices-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped w-100" id="invoicesTable">
                                            <thead>
                                                <tr>
                                                    <th scope="col"><span
                                                            class="text-uppercase fs-12 text-gray">{{ __('dashboard.modal.user_details.table.id') }}</span>
                                                    </th>
                                                    <th scope="col"><span
                                                            class="text-uppercase fs-12 text-gray">{{ __('dashboard.modal.user_details.table.number') }}</span>
                                                    </th>
                                                    <th scope="col"><span
                                                            class="text-uppercase fs-12 text-gray">{{ __('dashboard.modal.user_details.table.points') }}</span>
                                                    </th>
                                                    <th scope="col"><span
                                                            class="text-uppercase fs-12 text-gray">{{ __('dashboard.modal.user_details.table.cashback') }}</span>
                                                    </th>
                                                    <th scope="col"><span
                                                            class="text-uppercase fs-12 text-gray">{{ __('dashboard.modal.user_details.table.date') }}</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($user->invoices as $invoice)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $invoice->invoice_number ?? 'N/A' }}</td>
                                                        <td>{{ optional($invoice->userPoints)->points ?? 0 }}</td>
                                                        <td>{{ optional($invoice->userPoints)->cashback ?? 0 }}</td>
                                                        <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            {{ __('dashboard.modal.user_details.no_invoices') }}</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Point Deductions Tab -->
                                <div class="tab-pane fade" id="deductions" role="tabpanel"
                                    aria-labelledby="deductions-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('dashboard.deduction_history.date') }}</th>
                                                    <th>{{ __('dashboard.deduction_history.invoice_number') }}</th>
                                                    <th>{{ __('dashboard.deduction_history.points') }}</th>
                                                    <th>{{ __('dashboard.deduction_history.reason') }}</th>
                                                    <th>{{ __('dashboard.deduction_history.deducted_by') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($user->pointDeductionHistories as $deduction)
                                                    <tr>
                                                        <td>{{ $deduction->created_at->format('Y-m-d H:i:s') }}</td>
                                                        <td>{{ optional($deduction->invoice)->invoice_number ?? 'N/A' }}
                                                        </td>
                                                        <td>{{ $deduction->points_deducted }}</td>
                                                        <td>{{ $deduction->reason }}</td>
                                                        <td>{{ optional($deduction->deductedBy)->name ?? 'N/A' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            {{ __('dashboard.deduction_history.no_records') }}</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Points & Amount Modal -->
                <div class="modal fade" id="pointsModal" tabindex="-1" aria-labelledby="pointsModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('points.store') }}" method="POST">
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
                                            placeholder="{{ __('dashboard.modal.points.amount_placeholder') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="points"
                                            class="form-label">{{ __('dashboard.modal.points.points') }}</label>
                                        <input type="number" name="points" id="points" class="form-control"
                                            placeholder="{{ __('dashboard.modal.points.points_placeholder') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cashback"
                                            class="form-label">{{ __('dashboard.modal.points.cashback') }}</label>
                                        <input type="number" name="cashback" id="cashback" class="form-control"
                                            placeholder="{{ __('dashboard.modal.points.cashback_placeholder') }}"
                                            required>
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

                <!-- Edit User Modal -->
                <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('users.update', $user->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-content rounded-4">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel">
                                        {{ __('dashboard.modal.edit_user.title') }}: {{ $user->name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="{{ __('dashboard.modal.edit_user.close') }}"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('auth.name') }}</label>
                                        <input type="text" name="name" id="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ $user->name }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label"> {{ __('auth.email') }}</label>
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ $user->email }}" readonly>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">{{ __('auth.phone') }}</label>
                                        <input type="text" name="phone" id="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ $user->phone }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">
                                            {{ __('dashboard.modal.edit_user.change_image') }}
                                        </label>
                                        <input type="file" name="image" id="image" class="form-control"
                                            accept="image/*">
                                        <div class="mt-2 text-center">
                                            @if ($user->photo)
                                                <img src="{{ asset('profile_images/' . $user->photo) }}"
                                                    alt="{{ __('dashboard.modal.edit_user.image_preview_alt') }}"
                                                    class="img-thumbnail" style="max-width: 150px;">
                                            @else
                                                <img id="avatarPreview" src="{{ asset('template/images/profile/1.jpg') }}"
                                                    alt="{{ __('dashboard.modal.edit_user.image_preview_alt') }}"
                                                    class="img-thumbnail" style="max-width: 150px;">
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit"
                                        class="buttonsave btn-shines">{{ __('dashboard.modal.edit_user.save') }}</button>
                                    <button type="button" class="btn btn-danger"
                                        data-bs-dismiss="modal">{{ __('dashboard.modal.add_admin.cancel') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

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
                                        <input type="password" name="password" id="password" class="form-control"
                                            required placeholder="{{ __('dashboard.modal.add_admin.enter_password') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_confirmation"
                                            class="form-label">{{ __('dashboard.modal.add_admin.confirm_password') }}</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" required
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

                <!-- Delete User Modal -->
                <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-content rounded-4">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserModalLabel">
                                        {{ __('dashboard.modal.user_details.delete') }}: {{ $user->name }}
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
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addInvoiceModalLabel-{{ $user->id }}">
                                        {{ __('dashboard.modal.user_details.add_invoice') }}:
                                        {{ $user->name }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="{{ __('dashboard.close') }}"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="mb-3">
                                        <label for="invoice_number-{{ $user->id }}" class="form-label">
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
                                            id="invoice_total-{{ $user->id }}" class="form-control" step="0.01"
                                            placeholder="{{ __('dashboard.modal.invoice.invoice_total_placeholder') }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="invoice-date-{{ $user->id }}" class="form-label">
                                            {{ __('dashboard.modal.invoice.date') }}
                                        </label>
                                        <input type="date" name="invoice_date" id="invoice-date-{{ $user->id }}"
                                            class="form-control" required>
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
            </div>
        </section>
    </div>
@endsection
