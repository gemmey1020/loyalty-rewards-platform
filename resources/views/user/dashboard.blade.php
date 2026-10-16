@extends('layouts.app')

@section('title', __('dashboard.dashboard_user.dashboard'))

@section('content')
    <!-- content begin -->
    <div class="no-bottom no-top zebra" id="content">
        <div id="top"></div>
{{--        @dd( $image)--}}

        <!-- section begin -->
        <section id="subheader" class="text-light" >
            <div class="subheader-overlay"  style="background-image: url('{{ $image }}') !important;"></div>

            <div class="subheader-content center-y text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center hover-underline">
                            {{ __('dashboard.dashboard_user.dashboard') }}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>

{{--        $image --}}

        <!-- section close -->

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
                                    @endif <button type="button" class="butonIconEfit"
                                        data-bs-toggle="modal" data-bs-target="#editUserModal-static" title="Edit User">
                                        <i class="fa fa-pencil editicon"></i>
                                    </button>
                                </div>
                                <div class="profile_name ">
                                    <h4>
                                        {{ Auth::user()->name ?? 'user' }}
                                        <span class="profile_username text-gray">{{ Auth::user()->email ?? 'Admin' }}</span>
                                    </h4>

                                </div>
                            </div>
                            <div class="spacer-20"></div>
                            <ul class="menu-col">
                                <li><a href="" class="active"><i
                                            class="fa fa-home"></i>{{ __('dashboard.dashboard_user.dashboard') }}</a>
                                </li>

                                <li><button class="border-0" data-bs-toggle="modal" data-bs-target="#editUserModal-static"
                                        style="background-color: #fff;"><i class=" fa fa-user"
                                            style="color: #0e69b2 ; "></i>{{ __('dashboard.dashboard_user.profile') }}</button>
                                </li>

                                <li>
                                    <form action="{{ route('logout') }}" method="POST"
                                          style="display: flex; align-items: center; padding: 5px 10px !important;">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline w-100 fs-6"
                                                style="text-decoration: none; color: #333333; text-align: justify !important;">
                                            <i class="fa fa-sign-out me-2 text-danger"></i> {{ __('dashboard.sidebar.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row flex-column flex-sm-row justify-content-around align-items-center">

                            <x-stat-card icon="fa-file-text" :count="$invoices->count()" :label="__('dashboard.stats.invoices')" />

                            <x-stat-card icon="fa-tags" :count="$points->sum('net_points')" :label="__('dashboard.dashboard_user.points')" />

                            <x-stat-card icon="fa-money" :count="$points->sum('net_cashback')" :label="__('dashboard.dashboard_user.cashback')" />

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
                        <!-- Left-to-right version (English) -->
                        <div class="progress-container">
                            <div class="labels-top">
                                <div>{{ __('dashboard.dashboard_user.points') }}</div>
                                <div>{{ __('dashboard.dashboard_user.points') }}</div>
                                <div>{{ __('dashboard.dashboard_user.points') }}</div>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar"></div>
                            </div>
                            <div class="labels-bottom">
                                <div>0</div>
                                <div>{{ $points->sum('net_points') ?? 0 }}</div>
                                <div>Target Point</div>
                            </div>
                        </div>

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
                                        <table id="invoicesTable" class="table table-striped w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('dashboard.table.id') }}</th>
                                                    <th>{{ __('dashboard.table.amount') }}</th>
                                                    <th>{{ __('dashboard.table.points') }}</th>
                                                    <th>{{ __('dashboard.table.cashback') }}</th>
                                                    <th>{{ __('dashboard.table.date') }}</th>
                                                    <th>{{ __('dashboard.table.action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoices as $invoice)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ number_format($invoice->invoice_total, 2) }}</td>
                                                        <td>{{ $invoice->userPoints->points ?? '0' }}</td>
                                                        <td>{{ $invoice->userPoints->cashback ?? '0' }}</td>
                                                        <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                                data-bs-target="#userHistoryModal-{{ $invoice->id }}">
                                                                <i class="fa fa-history"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                @foreach ($invoices as $invoice)
                                    <div class="modal fade" id="userHistoryModal-{{ $invoice->id }}" tabindex="-1"
                                        aria-labelledby="userHistoryModalLabel-{{ $invoice->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content rounded-4">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        {{ __('dashboard.invoice_history.title') }}:
                                                        {{ $invoice->invoice_number ?? 'N/A' }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if ($invoice->editHistory->count() > 0)
                                                        <div class="timeline">
                                                            @foreach ($invoice->editHistory as $history)
                                                                <div class="history-item mb-4 border-left p-3">
                                                                    <div class="text-muted">
                                                                        <strong>{{ $history->editor->name ?? 'N/A' }}</strong>
                                                                        {{ __('dashboard.invoice_history.edited_by') }}
                                                                        {{ $history->created_at->format('M d, Y H:i') }}
                                                                    </div>
                                                                    <div class="changes mt-2">
                                                                        @foreach ($history->changes as $field => $values)
                                                                            <div class="change-item">
                                                                                <strong>
                                                                                    @if ($field === 'invoice_number')
                                                                                        {{ __('dashboard.invoice_history.changes.invoice_number') }}
                                                                                    @elseif($field === 'invoice_total')
                                                                                        {{ __('dashboard.invoice_history.changes.invoice_total') }}
                                                                                    @elseif($field === 'invoice_date')
                                                                                        {{ __('dashboard.invoice_history.changes.invoice_date') }}
                                                                                    @else
                                                                                        {{ $field }}
                                                                                    @endif:
                                                                                </strong>
                                                                                <div class="d-flex gap-2">
                                                                                    <span
                                                                                        class="text-danger">Previous:</span>
                                                                                    <span
                                                                                        class="text-danger">{{ $history->$field }}</span>
                                                                                    <i class="fa fa-arrow-right"></i>
                                                                                    <span class="text-success">Changed
                                                                                        To:</span>
                                                                                    <span
                                                                                        class="text-success">{{ $values }}</span>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-muted">
                                                            {{ __('dashboard.invoice_history.no_history') }}</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        {{ __('dashboard.close') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach



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
                                                @forelse(Auth::user()->pointDeductionHistories as $deduction)
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

                        {{-- Edit User Modal --}}
                        <div class="modal fade" id="editUserModal-static" tabindex="-1"
                            aria-labelledby="editUserModalLabel-static" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('users.update', Auth::user()->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content rounded-4">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel-static">
                                                {{ __('dashboard.dashboard_user.edit_user') }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="name"
                                                    class="form-label">{{ __('dashboard.dashboard_user.name') }}</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    value="{{ Auth::user()->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email"
                                                    class="form-label">{{ __('dashboard.dashboard_user.email') }}</label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    value="{{ Auth::user()->email }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone"
                                                    class="form-label">{{ __('dashboard.dashboard_user.phone') }}</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    value="{{ Auth::user()->phone }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="image"
                                                    class="form-label">{{ __('dashboard.dashboard_user.change_image') }}</label>
                                                <input type="file" name="image" id="image" class="form-control"
                                                    accept="image/*">
                                                @if (Auth::user()->photo)
                                                    <div class="mt-2 text-center">
                                                        <img id="avatarPreview"
                                                            src="{{ asset('profile_images/' . Auth::user()->photo) }}"
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
                                            <button type="submit" class="buttonsave btn-shine">
                                                <span>{{ __('dashboard.dashboard_user.save') }}</span>
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                                {{ __('dashboard.dashboard_user.cancel') }}
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editForm = document.querySelector('#editUserModal-static form');
            const nameInput = editForm.querySelector('#name');
            const phoneInput = editForm.querySelector('#phone');
            const imageInput = document.getElementById('image');
            const preview = document.getElementById('avatarPreview');

            // 🔒 Block alphabetic input in phone field
            if (phoneInput) {
                phoneInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            // 📸 Preview uploaded image whether previous image exists or not
            if (imageInput && preview) {
                imageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        preview.src = URL.createObjectURL(file);
                    } else {
                        preview.src = "{{ asset('template/images/profile/1.jpg') }}";
                    }
                });
            }

            // ✅ Form validation
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    let valid = true;
                    let errors = [];

                    const name = nameInput.value.trim();
                    const phone = phoneInput.value.trim();
                    const phonePattern = /^[569]\d{7}$/;

                    if (!name) {
                        valid = false;
                        errors.push(
                            "{{ __('validation.required', ['attribute' => __('dashboard.dashboard_user.name')]) }}"
                        );
                    }

                    if (!phonePattern.test(phone)) {
                        valid = false;
                        errors.push(
                            "{{ __('validation.regex', ['attribute' => __('dashboard.dashboard_user.phone')]) }}"
                        );
                    }

                    if (!valid) {
                        e.preventDefault();
                        showFormErrors(errors);
                    }
                });
            }

            function showFormErrors(errors) {
                const existingAlert = editForm.querySelector('.alert-danger');
                if (existingAlert) existingAlert.remove();

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
    </script>
@endpush
