<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\SendOtpNotification;
use App\Notifications\PasswordChangedNotification;
use App\Models\User; // Import model user
use App\Notifications\AdminPasswordChangeNotification;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class PasswordController extends Controller
{
    /**
     * Mengirimkan kode OTP ke email user.
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Generate 6 digit angka
        $otp = rand(100000, 999999);

        // Simpan ke database dengan masa berlaku 15 menit
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // Kirim email
        $user->notify(new SendOtpNotification($otp));

        return back()->with('status', 'otp-sent');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'otp' => ['required', 'string'], // Input OTP baru
        ]);

        $user = $request->user();

        // Validasi OTP
        if ($user->otp_code !== $request->otp || Carbon::now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.'], 'updatePassword');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'otp_code' => null, // Reset OTP setelah berhasil
            'otp_expires_at' => null,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'activity' => 'Ubah Password',
            'description' => 'User ' . $user->name . ' berhasil mengubah password menggunakan OTP.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Kirim notifikasi berhasil (seperti yang kita buat sebelumnya)
        $user->notify(new PasswordChangedNotification());
        $admin = User::where('email', 'admin@siakad.test')->first(); 
        if ($admin) {
            $admin->notify(new AdminPasswordChangeNotification($user));
        }

        return back()->with('status', 'password-updated');
    }
}
