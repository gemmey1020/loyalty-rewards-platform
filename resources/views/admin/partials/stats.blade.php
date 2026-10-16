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
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
