<!-- Admin Sidebar -->
<div class="col-lg-3 mb30">
    <div class="card p-4 rounded-5">
        <div class="profile_avatar">
            <div class="profile_img position-relative">
                @if (Auth::user()->photo)
                    <img src="{{ asset('profile_images/' . Auth::user()->photo) }}" alt="">
                @else
                    <img src="{{ asset('template/images/profile/1.jpg') }}" alt="">
                @endif
                <button type="button" class="butonIconEfit" data-bs-toggle="modal" data-bs-target="#editAdminModal"
                    title="{{ __('dashboard.modal.edit_admin.title') }}">
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
            <li>
                <a class="{{ Request::routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-home"></i>{{ __('dashboard.sidebar.dashboard') }}
                </a>
            </li>
            <li>
                <a class="{{ Request::routeIs('admins.invoices.index') ? 'active' : '' }}"
                    href="{{ route('admins.invoices.index') }}" id="load-invoices">
                    <i class="fa fa-file-text"></i>{{ __('dashboard.sidebar.all_invoices') }}
                </a>
            </li>
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
                <li>
                    <a class="{{ Request::routeIs('system-setup.index') ? 'active' : '' }}"
                       href="{{ route('system-setup.index') }}" id="#">
                        <i class="fa fa-edit"></i>{{ __('dashboard.sidebar.edit_banner') }}
                    </a>
                </li>

            @endif
            <li>
                <form action="{{ route('logout') }}" method="POST"
                    style="display: flex; align-items: center; padding: 5px 10px !important;">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline w-100 fs-6"
                        style="text-decoration: none; color: #333333; text-align: justify !important;">
                        <i class="fa fa-sign-out me-2 text-danger"></i>{{ __('dashboard.sidebar.logout') }}
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
