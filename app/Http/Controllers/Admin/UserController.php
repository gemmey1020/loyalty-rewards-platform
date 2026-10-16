<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user->load(['invoices.userPoints', 'userPoints']);

        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^(?:\+965|00965)?[569]\d{7}$/'],
        ]);

        // Update basic fields
        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($user->photo) {
                $oldPath = public_path('profile_images/'.$user->photo);
                if (file_exists($oldPath)) {
                    @unlink($oldPath); // Suppress error if file doesn't exist
                }
            }

            // Upload new image
            $filename = uniqid('user_').'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('profile_images'), $filename);

            // Save only the filename to DB
            $user->photo = $filename;
        }

        // Save the user
        $user->save();

        return redirect()->back()->with('success', __('message.user_updated'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'string', 'max:20', 'regex:/^(?:\+965|00965)?[569]\d{7}$/', 'unique:users,phone'.$request->id],
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'nullable|date|before:today',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
            'role' => UserRole::USER->value,
        ];

        // ✅ Handle image upload (before user creation)
        if ($request->hasFile('image')) {
            $filename = uniqid('user_').'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('profile_images'), $filename);

            // Save the image filename to $userData
            $userData['photo'] = $filename;
        }

        User::create($userData);

        return redirect()->route('admin.dashboard')->with('success', __('message.user_created'));
    }

    public function destroy(User $user)
    {
        if ($user->photo) {
            $oldPath = public_path('profile_images/'.$user->photo);
            if (file_exists($oldPath)) {
                @unlink($oldPath); // Suppress error if file doesn't exist
            }
        }
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', __('message.user_deleted'));
    }

    public function deductionHistory(User $user)
    {
        $deductions = $user->pointDeductionHistories()
            ->with(['invoice', 'userPoint', 'deductedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.deduction-history', compact('user', 'deductions'));
    }
}
