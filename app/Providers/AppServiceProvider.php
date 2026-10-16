<?php

namespace App\Providers;

use App\Services\Contracts\UserNotifierInterface;
use App\Services\Services\UserNotifier;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserNotifierInterface::class, UserNotifier::class);
    }

    public function boot(): void
    {
        // Set locale from session if it exists, otherwise use config default
        $locale = Session::get('locale', Config::get('app.locale'));

        // Ensure locale is supported, otherwise use fallback
        if (! in_array($locale, ['en', 'ar'])) {
            $locale = Config::get('app.fallback_locale', 'en');
        }

        // Set both session and application locale
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
}
