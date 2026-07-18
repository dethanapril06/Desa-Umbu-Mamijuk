<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->fill($request->only('name', 'email'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Informasi profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $this->normalizeInput($request);

        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($request) {
                if (!Hash::check($value, $request->user()->password)) {
                    $fail('Password saat ini salah.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Normalisasi & pembersihan input sebelum validasi.
     */
    private function normalizeInput(Request $request): void
    {
        if ($request->has('name') && is_string($request->input('name')) && !empty($request->input('name'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('name')));
            $cleaned = mb_convert_case(mb_strtolower($cleaned, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $request->merge(['name' => $cleaned]);
        }

        if ($request->has('email') && is_string($request->input('email')) && !empty($request->input('email'))) {
            $cleaned = preg_replace('/\s+/', ' ', trim($request->input('email')));
            $request->merge(['email' => $cleaned]);
        }

        $passwordFields = ['current_password', 'password', 'password_confirmation'];
        foreach ($passwordFields as $field) {
            if ($request->has($field) && is_string($request->input($field)) && !empty($request->input($field))) {
                $cleaned = trim($request->input($field));
                $request->merge([$field => $cleaned]);
            }
        }
    }
}
