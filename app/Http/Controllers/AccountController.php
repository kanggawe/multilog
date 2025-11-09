<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Show user profile page.
     */
    public function profile()
    {
        $user = auth()->user();
        return view('account.profile', [
            'user' => $user,
            'page' => 'profile',
            'showBreadcrumb' => true,
        ]);
    }

    /**
     * Show settings page.
     */
    public function settings()
    {
        $user = auth()->user();
        return view('account.settings', [
            'user' => $user,
            'page' => 'settings',
            'showBreadcrumb' => true,
        ]);
    }

    /**
     * Show edit profile page.
     */
    public function editProfile()
    {
        $user = auth()->user();
        return view('account.edit-profile', [
            'user' => $user,
            'page' => 'profile',
            'showBreadcrumb' => true,
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show change password page.
     */
    public function changePassword()
    {
        return view('account.change-password', [
            'page' => 'profile',
            'showBreadcrumb' => true,
        ]);
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('account.profile')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Show help page.
     */
    public function help()
    {
        return view('account.help', [
            'page' => 'help',
            'showBreadcrumb' => true,
        ]);
    }

    /**
     * Update user settings.
     */
    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'language' => ['nullable', 'string', 'in:en,id,es,fr'],
            'timezone' => ['nullable', 'string'],
            'date_format' => ['nullable', 'string', 'in:Y-m-d,d/m/Y,m/d/Y'],
            'theme' => ['nullable', 'string', 'in:light,dark,auto'],
            'email_notifications' => ['nullable', 'boolean'],
            'push_notifications' => ['nullable', 'boolean'],
        ]);

        $user->update($validated);

        return redirect()->route('account.settings')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}
