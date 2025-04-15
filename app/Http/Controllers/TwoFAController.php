<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use PragmaRX\Google2FALaravel\Support\QRCode;
use PragmaRX\Google2FALaravel\Support\QRCodeGenerator;
use PragmaRX\Google2FALaravel\Support\QRCodeInline;
use PragmaRX\Google2FALaravel\Support\QRCodeInlineGenerator;

class TwoFAController extends Controller
{
    public function show2faSetup()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        if (!$user->google2fa_secret) {
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $QR_Image = $google2fa->getQRCodeInline(
            'Nama Aplikasi',
            $user->email,
            $user->google2fa_secret
        );

        return view('auth.2fa_setup', [
            'qrCode' => $QR_Image,
            'secret' => $user->google2fa_secret
        ]);
    }

    public function showVerifyPage()
    {
        $user = Auth::user();

        if (!$user->google2fa_secret) {
            return redirect()->route('2fa.setup');
        }

        return view('auth.2fa_verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $google2fa = new Google2FA();
        $user = Auth::user();

        if ($google2fa->verifyKey($user->google2fa_secret, $request->otp)) {
            session(['2fa_passed' => true]);
            return redirect()->route('dashboard.home');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah']);
    }

    public function disable2FA(Request $request)
    {
        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->save();

        session()->forget('2fa_passed');

        return redirect()->back()->with('status', 'Two-Factor Authentication berhasil dinonaktifkan.');
    }
}
