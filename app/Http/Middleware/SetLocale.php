<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session or config
        $locale = session('locale', config('app.locale', 'en'));

        // 🔐 Ensure locale is a string and valid
        if (! is_string($locale) || ! in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }

        // ✅ Set session values safely
        session([
            'locale' => $locale,
            'dir' => $locale === 'ar' ? 'rtl' : 'ltr',
        ]);

        // ✅ Set Laravel app locale
        App::setLocale($locale);

        return $next($request);
    }
}
