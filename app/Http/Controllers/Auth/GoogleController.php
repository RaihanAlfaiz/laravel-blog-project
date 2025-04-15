<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'username' => $this->generateUsername($googleUser->getEmail()),
                    'profile' => $googleUser->getAvatar(),
                    'about' => null,
                    'role' => 1,
                    'status' => true,
                    'email_verified_at' => Carbon::now(),
                    'password' => bcrypt(Str::random(16)),
                ]
            );

            Auth::login($user);
            return redirect()->route('dashboard.home');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors('Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    private function generateUsername($email)
    {
        $username = explode('@', $email)[0];

        // Cek kalau username sudah dipakai
        $original = $username;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $original . $i++;
        }

        return $username;
    }
}
