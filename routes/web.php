<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\SystemSetupController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\User\DashboardController;
use App\Models\SystemSetup;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/lang/{locale}', function ($locale) {
        if (! in_array($locale, ['en', 'ar'])) {
            return back()->with('error', 'Unsupported language selected.');
        }

        session()->put('locale', $locale);
        app()->setLocale($locale);
        session()->save();

        return redirect()->back();
    })->name('lang.switch');
});

Route::middleware('guest')->group(function () {

    Route::get('/', function () {

        $image = SystemSetup::getDefaultImage('login');

        return view('auth.login', compact('image'));

    })->name('login');

    Route::get('/login', function () {
        return redirect()->route('login');
    });

    Route::post('/login', LoginController::class)->name('login.post');

    Route::get('/register', function () {

        $image = SystemSetup::getDefaultImage('register');

        return view('auth.register', compact('image'));

    })->name('register');

    Route::post('/register', RegisterController::class)->name('register.post');

    Route::get('/activate/{token}', ActivationController::class)->name('activate');

    // forget password
    Route::get('/forgot-password', [ForgetPasswordController::class, 'showForgotForm'])->name('get.password.forget');
    Route::post('/forgot-password', [ForgetPasswordController::class, 'sendResetLink'])->middleware('throttle:6,1')->name('post.password.forget');
    Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('password.update');

});

Route::middleware('auth')->group(function () {

     Route::middleware('user')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    });

    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::middleware('admin')->group(function () {
        Route::post('/points', [PointController::class, 'store'])->name('points.store');
        // create invoice
        Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // Route::get('/admin/invoices', [AdminDashboardController::class, 'index'])->name('admins.invoices.index');

        Route::get('/admin/invoices', [InvoiceController::class, 'index'])->name('admins.invoices.index');

        Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroyInvoice'])->name('invoices.destroy');

        Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admins.users.show');
        Route::get('/admin/users/{user}/deduction-history', [UserController::class, 'deductionHistory'])->name('admins.users.deduction-history');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // store admin
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
        // update admin
        Route::put('/admins/{id}', [AdminController::class, 'update'])->name('admins.update');
        // all admins
        Route::get('/admins', [AdminController::class, 'allAdmins'])->name('admins.index');

        Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
        Route::get('/admin/{id}', [AdminController::class, 'show'])->name('admin.show');
        Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');

        // Add this new route
        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        // discountPoints.store
        Route::post('/discountPoints/{userId}', [PointController::class, 'discount'])->name('discountPoints.store');

        // system setup

        Route::prefix('system-setup')->name('system-setup.')->group(function () {

            Route::get('/', [SystemSetupController::class, 'index'])->name('index');

            Route::post('/', [SystemSetupController::class, 'store'])->name('store');

            Route::get('/show', [SystemSetupController::class, 'show'])->name('show');

            Route::get('/make-default/{id}', [SystemSetupController::class, 'makeDefault'])->name('make-default');

            Route::get('/destroy/{id}', [SystemSetupController::class, 'destroy'])->name('destroy');

        });
    });

    Route::post('/logout', function () {
        auth()->logout();

        return redirect()->route('login');
    })->name('logout');
});
