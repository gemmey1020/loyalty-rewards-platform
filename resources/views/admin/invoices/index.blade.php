@extends('layouts.app')

@section('title', 'Admin Panel')

@section('styles')
    <style>
        .timeline .history-item {
            border-left: 3px solid #0d6efd;
            position: relative;
            padding-left: 20px;
            margin-bottom: 30px;
        }

        .timeline .history-item:before {
            content: '';
            width: 12px;
            height: 12px;
            background: #0d6efd;
            border-radius: 50%;
            position: absolute;
            left: -7px;
            top: 8px;
        }

        .change-item {
            margin-top: 10px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
        }
    </style>
@endsection

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

                            <!-- Users Card -->
                            <x-stat-card url="{{ url('/admin/dashboard') }}" icon="fa-user" count="{{ $usersCount }}"
                                label="{{ __('dashboard.stats.users') }}" />

                            <!-- Admins Card -->
                            @if (Auth::user()->role == App\Enums\UserRole::SUPER_ADMIN->value)
                                <x-stat-card url="{{ url('/admins') }}" icon="fa-user-secret" count="{{ $adminsCount }}"
                                    label="{{ __('dashboard.stats.admins') }}" />
                            @endif

                            <!-- Invoices Card -->
                            <x-stat-card url="{{ url('/admin/invoices') }}" icon="fa-file-text"
                                count="{{ $invoicesCount }}" label="{{ __('dashboard.stats.invoices') }}" />
                        </div>


                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
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

                        <div class="card p-4 rounded-5 mb-4">
                            <h4 id="table-title" class="mb-3">{{ __('dashboard.recent_invoices') }}</h4>

                            <div class="table-responsive">
                                <table id="recentInvoicesTable" class="table  table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>{{ __('dashboard.table.id') }}</th>
                                            <th>{{ __('dashboard.table.invoice_number') }}</th>
                                            <th>{{ __('dashboard.table.user_name') }}</th>
                                            <th>{{ __('dashboard.table.amount') }}</th>
                                            <th>{{ __('dashboard.table.date') }}</th>
                                            <th>{{ __('dashboard.table.points') }}</th>
                                            <th>{{ __('dashboard.table.cashback') }}</th>
                                            <th>{{ __('dashboard.table.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $invoice)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $invoice->invoice_number ?? 'N/A' }}</td>
                                                <td>{{ $invoice->user->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($invoice->invoice_total, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}
                                                </td>
                                                <td>{{ $invoice->userPoints->points ?? 'N/A' }}</td>
                                                <td>{{ $invoice->userPoints->cashback ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <button type="button" class="btn btn-danger btn-sm me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteInvoiceModal-{{ $invoice->id }}"
                                                            title="Delete Invoice">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-primary btn-sm me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editInvoiceModal-{{ $invoice->id }}"
                                                            title="Edit Invoice">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#historyModal-{{ $invoice->id }}"
                                                            title="View History">
                                                            <i class="fa fa-history"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- Modals -->
        <!-- Add Admin Modal -->
        <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admins.store') }}" method="POST">
                    @csrf
                    <div class="modal-content rounded-4">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addAdminModalLabel">{{ __('dashboard.modal.add_admin.title') }}
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
                                <input type="text" name="name" id="name" class="form-control" required
                                    value="{{ old('name') }}"
                                    placeholder="{{ __('dashboard.modal.add_admin.enter_name') }}">
                            </div>
                            <div class="mb-3">
                                <label for="email"
                                    class="form-label">{{ __('dashboard.modal.add_admin.email') }}</label>
                                <input type="text" name="email" id="email" class="form-control" required
                                    value="{{ old('email') }}"
                                    placeholder="{{ __('dashboard.modal.add_admin.enter_email') }}">
                            </div>
                            <div class="mb-3">
                                <label for="phone"
                                    class="form-label">{{ __('dashboard.modal.add_admin.phone') }}</label>
                                <input type="text" name="phone" id="phone" class="form-control" required
                                    value="{{ old('phone') }}"
                                    placeholder="{{ __('dashboard.modal.add_admin.enter_phone') }}">
                            </div>
                            <div class="mb-3">
                                <label for="password"
                                    class="form-label">{{ __('dashboard.modal.add_admin.password') }}</label>
                                <input type="password" name="password" id="password" class="form-control" required
                                    placeholder="{{ __('dashboard.modal.add_admin.enter_password') }}">
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


        <!-- Points & Amount Modal -->
        <div class="modal fade" id="pointsModal" tabindex="-1" aria-labelledby="pointsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('points.store') }}" method="POST">
                    @csrf
                    <div class="modal-content rounded-4">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pointsModalLabel">{{ __('dashboard.modal.points.title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="{{ __('dashboard.modal.points.close') }}"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="amount"
                                    class="form-label">{{ __('dashboard.modal.points.amount') }}</label>
                                <input type="number" name="amount" id="amount" class="form-control" step="0.01"
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
                                    placeholder="{{ __('dashboard.modal.points.cashback_placeholder') }}" required>
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

        <!-- Invoice Modals -->
        @foreach ($invoices as $invoice)
            <!-- Delete Invoice Modal -->
            <div class="modal fade" id="deleteInvoiceModal-{{ $invoice->id }}" tabindex="-1"
                aria-labelledby="deleteInvoiceModalLabel-{{ $invoice->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-content rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteInvoiceModalLabel-{{ $invoice->id }}">
                                    {{ __('dashboard.modal.invoice.delete_title') }}:
                                    {{ $invoice->invoice_number ?? 'N/A' }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="{{ __('dashboard.modal.invoice.close') }}"></button>
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

            <!-- Edit Invoice Modal -->
            <div class="modal fade" id="editInvoiceModal-{{ $invoice->id }}" tabindex="-1"
                aria-labelledby="editInvoiceModalLabel-{{ $invoice->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editInvoiceModalLabel-{{ $invoice->id }}">
                                    {{ __('dashboard.modal.invoice.edit_title') }}:
                                    {{ $invoice->invoice_number ?? 'N/A' }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="{{ __('dashboard.modal.invoice.close') }}"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="invoice_number-{{ $invoice->id }}"
                                        class="form-label">{{ __('dashboard.modal.invoice.invoice_number') }}</label>
                                    <input type="text" name="invoice_number" id="invoice_number-{{ $invoice->id }}"
                                        class="form-control"
                                        placeholder="{{ __('dashboard.modal.invoice.invoice_number_placeholder') }}"
                                        value="{{ $invoice->invoice_number ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label for="invoice_total-{{ $invoice->id }}"
                                        class="form-label">{{ __('dashboard.modal.invoice.invoice_total') }}</label>
                                    <input type="number" name="invoice_total" id="invoice_total-{{ $invoice->id }}"
                                        class="form-control" step="0.01"
                                        placeholder="{{ __('dashboard.modal.invoice.invoice_total_placeholder') }}"
                                        value="{{ $invoice->invoice_total }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="invoice_date-{{ $invoice->id }}"
                                        class="form-label">{{ __('dashboard.modal.invoice.date') }}</label>
                                    <input type="date" name="invoice_date" id="invoice_date-{{ $invoice->id }}"
                                        class="form-control"
                                        value="{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}"
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

            <!-- History Modal -->
            <div class="modal fade" id="historyModal-{{ $invoice->id }}" tabindex="-1"
                aria-labelledby="historyModalLabel-{{ $invoice->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-4">
                        <div class="modal-header">
                            <h5 class="modal-title" id="historyModalLabel-{{ $invoice->id }}">
                                {{ __('dashboard.invoice_history.title') }}: {{ $invoice->invoice_number ?? 'N/A' }}
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
                                                <strong>{{ $history->editor->name }}</strong>
                                                {{ __('dashboard.invoice_history.edited_by') }}
                                                {{ $history->created_at->format('M d, Y H:i') }}
                                            </div>
                                            <div class="changes mt-2">
                                                @foreach ($history->changes as $field => $values)
                                                    <div class="change-item">
                                                        <strong>
                                                            @if ($field === 'invoice_number')
                                                                {{ __('dashboard.invoice_history.changes.invoice_number') }}
                                                            @elseif ($field === 'invoice_total')
                                                                {{ __('dashboard.invoice_history.changes.invoice_total') }}
                                                            @elseif ($field === 'invoice_date')
                                                                {{ __('dashboard.invoice_history.changes.invoice_date') }}
                                                            @else
                                                                {{ $field }}
                                                            @endif:
                                                        </strong>
                                                        <div class="d-flex gap-2">
                                                            <span class="text-danger">{{ __('dashboard.previous') }}:</span>
                                                            <span class="text-danger">{{ $history->$field }}</span>
                                                            <i class="fa fa-arrow-right"></i>
                                                            <span class="text-success">{{ __('dashboard.changed_to') }}:</span>
                                                            <span class="text-success">{{ $values }}</span>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">{{ __('dashboard.invoice_history.no_history') }}</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                {{ __('dashboard.close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    <!-- content close -->
@endsection

@section('scripts')
    <script>
        // Replace your current JavaScript with this
        document.getElementById('load-invoices').addEventListener('click', function(e) {
            e.preventDefault();

            // Update active class
            document.querySelector('.menu-col a.active')?.classList.remove('active');
            this.classList.add('active');

            // Show loading state
            const tableTitle = document.querySelector('#table-title');
            const tableBody = document.querySelector('#recentInvoicesTable tbody');

            tableTitle.textContent = 'Loading...';
            tableBody.innerHTML =
                '<tr><td colspan="8" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Use the direct URL instead of the route helper to ensure it works
            fetch('/admin/invoices', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                        // Update table title
                        tableTitle.textContent = 'All Invoices';

                        // Update table body with invoice data
                        tableBody.innerHTML = '';
                        if (data.invoices.length === 0) {
                            tableBody.innerHTML =
                                '<tr><td colspan="8" class="text-center">No invoices found.</td></tr>';
                            return;
                        }

                        data.invoices.forEach((invoice, index) => {
                            const formattedDate = invoice.invoice_date ? new Date(invoice.invoice_date)
                                .toISOString().split('T')[0] : 'N/A';
                            const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${invoice.invoice_number || 'N/A'}</td>
                                <td>${invoice.user?.name || 'N/A'}</td>
                                <td>${parseFloat(invoice.invoice_total).toFixed(2)}</td>
                                <td>${formattedDate}</td>
                                <td>${invoice.user_points?.points || 'N/A'}</td>
                                <td>${invoice.user_points?.cashback || 'N/A'}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#deleteInvoiceModal-${invoice.id}" title="Delete Invoice">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editInvoiceModal-${invoice.id}" title="Edit Invoice">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#historyModal-${invoice.id}" title="View History">
                                            <i class="fa fa-history"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });

                        // Remove existing dynamic modals
                        const existingModalContainer = document.getElementById('dynamic-invoice-modals');
                        if (existingModalContainer) {
                            existingModalContainer.remove();
                        }

                        // Create new modal container
                        const modalContainer = document.createElement('div');
                        modalContainer.id = 'dynamic-invoice-modals';
                        document.body.appendChild(modalContainer);

                        // Append modals (for AJAX-loaded invoices)
                        data.invoices.forEach(invoice => {
                                    const formattedDate = invoice.invoice_date ? new Date(invoice.invoice_date)
                                        .toISOString().split('T')[0] : '';
                                    const editModal = `
                            <div class="modal fade" id="editInvoiceModal-${invoice.id}" tabindex="-1" aria-labelledby="editInvoiceModalLabel-${invoice.id}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="/invoices/${invoice.id}" method="POST">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="PUT">
                                        <div class="modal-content rounded-4">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editInvoiceModalLabel-${invoice.id}">Edit Invoice: ${invoice.invoice_number || 'N/A'}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="invoice_number-${invoice.id}" class="form-label">Invoice Number</label>
                                                    <input type="text" name="invoice_number" id="invoice_number-${invoice.id}" class="form-control" value="${invoice.invoice_number || ''}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="invoice_total-${invoice.id}" class="form-label">Invoice Total</label>
                                                    <input type="number" name="invoice_total" id="invoice_total-${invoice.id}" class="form-control" step="0.01" value="${invoice.invoice_total}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="invoice_date-${invoice.id}" class="form-label">Date</label>
                                                    <input type="date" name="invoice_date" id="invoice_date-${invoice.id}" class="form-control" value="${formattedDate}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="buttonsave btn-shine">
                                                    <span>Save</span>
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>`;
                                    const deleteModal = `
                            <div class="modal fade" id="deleteInvoiceModal-${invoice.id}" tabindex="-1" aria-labelledby="deleteInvoiceModalLabel-${invoice.id}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="/invoices/${invoice.id}" method="POST">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <div class="modal-content rounded-4">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteInvoiceModalLabel-${invoice.id}">Delete Invoice: ${invoice.invoice_number || 'N/A'}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this invoice? This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>`;
                                    const historyModal = `
                            <div class="modal fade" id="historyModal-${invoice.id}" tabindex="-1" aria-labelledby="historyModalLabel-${invoice.id}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content rounded-4">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="historyModalLabel-${invoice.id}">Invoice History: ${invoice.invoice_number || 'N/A'}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ${invoice.edit_history?.length > 0 ?
                                                `<div class="timeline">
                                                            ${invoice.edit_history.map(history => `
                                                        <div class="history-item mb-4 border-left p-3">
                                                            <div class="text-muted">
                                                                <strong>${history.editor?.name || 'Unknown'}</strong> edited on ${new Date(history.created_at).toLocaleString()}
                                                            </div>
                                                            <div class="changes mt-2">
                                                                ${Object.entries(history.changes || {}).map(([field, values]) => {
                                                                    const fieldLabel = {
                                                                        'invoice_number': 'Invoice Number',
                                                                        'invoice_total': 'Invoice Total',
                                                                        'invoice_date': 'Invoice Date'
                                                                    }[field] || field;

                                                                    const oldValue = field === 'invoice_date'
                                                                        ? new Date(values.old).toLocaleDateString()
                                                                        : values.old;

                                                                    const newValue = field === 'invoice_date'
                                                                        ? new Date(values.new).toLocaleDateString()
                                                                        : values.new;

                                                                    return `
                                                                                <div class="change-item">
                                                                                    <strong>${fieldLabel}:</strong>
                                                                                    <div class="d-flex gap-2">
                                                                                        <span class="text-danger">${oldValue || 'N/A'}</span>
                                                                                        <i class="fa fa-arrow-right"></i>
                                                                                        <span class="text-success">${newValue || 'N/A'}</span>
                                                                                    </div>
                                                                                </div>
                                                                            `;
                                                                }).join('')}
                                                            </div>
                                                        </div>
                                                    `).join('')
                            } <
                            /div>`: '<p class="text-muted">No history available.</p > '
                } <
                /div> <
                div class = "modal-footer" >
                <
                button type = "button"
                class = "btn btn-secondary"
                data - bs - dismiss = "modal" > Close < /button> < /
                div > <
                /div> < /
                div > <
                /div>`;
                    modalContainer.insertAdjacentHTML('beforeend', editModal + deleteModal + historyModal);
                });
        })
        .catch(error => {
        console.error('Error fetching invoices:', error);
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Error loading invoices: ' +
            error.message +
            '</td></tr>';
        tableTitle.textContent = 'Error Loading Invoices';
        });
        });

        // Reload users when clicking Dashboard
        document.querySelector('.menu-col a[href*="admin/dashboard"]').addEventListener('click', function(e) {
            e.preventDefault();
            location.reload();
        });
    </script>
@endsection
