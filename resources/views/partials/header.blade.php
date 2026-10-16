<header class="posh-header">
  <div class="posh-container">
    <div class="posh-header-wrapper">
      <!-- Logo Area -->
      <div class="posh-logo">
        <img src="{{ asset('template/images/Posh_Center-1-removebg-preview.png') }}" alt="Posh Center Logo">
      </div>

      <!-- Right Side Menu -->
      <div class="posh-menu-side">
        @if (Auth::check())
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="posh-btn">{{ __('dashboard.sidebar.logout') }}</button>
        </form>
        @else
        @if (!Route::currentRouteName() == 'login')
        <a href="{{ route('login') }}" class="posh-btn">{{ __('auth.login_title') }}</a>
        @endif
        @endif

        <!-- Language Switcher -->
        <div class="posh-lang-switcher">
          <a href="{{ route('lang.switch', 'en') }}"
            class="{{ app()->getLocale() == 'en' ? 'posh-active-lang' : '' }}">EN</a>
          <span class="posh-lang-separator">|</span>
          <a href="{{ route('lang.switch', 'ar') }}"
            class="{{ app()->getLocale() == 'ar' ? 'posh-active-lang' : '' }}">AR</a>
        </div>
      </div>
    </div>
  </div>
</header>
