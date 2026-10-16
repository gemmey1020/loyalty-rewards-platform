<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SystemSetup;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $invoices = Auth::user()->invoices()->with(['editHistory.editor', 'userPoints'])->get();
        $points = Auth::user()->userPoints()->get();

        $image = SystemSetup::getDefaultImage('dashboard');

        if ($image) {
            $image = asset('banner/dashboard/'.$image->image);
        } else {
            $image = asset('/template/images/cars/pexels-wendywei-1662570.jpg');
        }

        return view('user.dashboard', compact(
            'invoices',
            'points',
            'image',
        ));
    }
}
