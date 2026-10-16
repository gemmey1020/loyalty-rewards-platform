<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $usersCount = User::where('role', UserRole::USER->value)->count();
        $adminsCount = User::where('role', UserRole::ADMIN->value)->count();
        $users = User::where('role', UserRole::USER->value)->get();
        $invoicesCount = Invoice::count();

        return view('admin.dashboard', compact('usersCount', 'adminsCount', 'users', 'invoicesCount'));
    }

    public function allAdmins()
    {
        $admins = User::where('role', UserRole::ADMIN->value)->get();

        $usersCount = User::where('role', UserRole::USER->value)->count();
        $adminsCount = User::where('role', UserRole::ADMIN->value)->count();
        $users = User::where('role', UserRole::USER->value)->get();
        $invoicesCount = Invoice::count();

        return view('admin.all_admin', compact('admins', 'usersCount', 'adminsCount', 'users', 'invoicesCount'));
    }

    public function show($id)
    {
        $admin = User::where('role', UserRole::ADMIN->value)->findOrFail($id);
        $usersCount = User::where('role', UserRole::USER->value)->count();
        $adminsCount = User::where('role', UserRole::ADMIN->value)->count();
        $users = User::where('role', UserRole::USER->value)->get();
        $invoicesCount = Invoice::count();

        return view('admin.show', compact('admin', 'usersCount', 'adminsCount', 'users', 'invoicesCount'));
    }

    public function edit($id)
    {
        $admin = User::where('role', UserRole::ADMIN->value)->findOrFail($id);
        $usersCount = User::where('role', UserRole::USER->value)->count();
        $adminsCount = User::where('role', UserRole::ADMIN->value)->count();
        $users = User::where('role', UserRole::USER->value)->get();
        $invoicesCount = Invoice::count();

        return view('admin.edit', compact('admin', 'usersCount', 'adminsCount', 'users', 'invoicesCount'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone', 'regex:/^(?:\+965|00965)?[569]\d{7}$/'],
        ];

        $request->validate($rules);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => UserRole::ADMIN->value,
        ]);

        return redirect()->route('admin.dashboard')->with('success', __('message.admin_created'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'phone' => ['required', 'string', 'max:255', 'regex:/^(?:\+965|00965)?[569]\d{7}$/', 'unique:users,phone,'.$id],
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            $oldPath = public_path('profile_images/'.$admin->photo);
            if ($admin->photo && file_exists($oldPath)) {
                unlink($oldPath); // Deletes the actual file from public folder
            }

            // Upload new image
            $filename = time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('profile_images'), $filename);

            // Save only the filename to DB
            $admin->photo = $filename;
        }

        $admin->save();

        return redirect()->back()->with('success', __('message.admin_updated'));
    }

    public function destroy($id)
    {
        $admin = User::where('role', UserRole::ADMIN->value)->findOrFail($id);

        // Don't allow deleting self
        if (Auth::id() === $admin->id) {
            return redirect()->back()->with('error', __('message.cannot_delete_self'));
        }

        // Delete admin's photo if exists
        $oldPath = public_path('profile_images/'.$admin->photo);
        if ($admin->photo && file_exists($oldPath)) {
            unlink($oldPath); // Deletes the actual file from public folder
        }

        $admin->delete();

        return redirect()->route('admins.index')->with('success', __('message.admin_deleted'));
    }
}
