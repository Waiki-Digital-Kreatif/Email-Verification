<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function register_page()
    {
        return view('auth.register_page');
    }

    public function verifyEmail(Request $request)
    {
        $user = User::findOrFail($request->id);

        if ($request->hasValidSignature()) {
            $user->markEmailAsVerified();
            event(new Verified($user));

            return redirect()->route('login_page')->with('message', 'Email Anda telah terverifikasi. Silakan login.');
        }

        return redirect()->route('login_page')->with('error', 'Link verifikasi tidak valid.');
    }

    public function register_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email|max:50',
            'password' => [
                'required',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#]/'
            ],
            'g-recaptcha-response' => 'required',
        ], [
            'password.regex' => 'Password harus mengandung minimal 8 karakter, termasuk huruf besar, huruf kecil, angka, dan karakter spesial.',
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRETKEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        $responseBody = $response->json();

        if (!$responseBody['success']) {
            return redirect()->back()->withErrors(['captcha' => 'Verifikasi CAPTCHA gagal, silakan coba lagi.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->sendEmailVerificationNotification();

        return redirect()->route('login_page')->with('message', 'Registrasi berhasil, silakan verifikasi email Anda.');
    }

    public function login_page()
    {
        return view('auth.login_page');
    }

    public function login_store(Request $request)
    {
        $key = 'login-attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->route('login_page')->with('error', 'Terlalu banyak percobaan login. Coba lagi dalam ' . $seconds . ' detik.');
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->hasVerifiedEmail()) {
                RateLimiter::clear($key); // Reset counter jika login berhasil
                return redirect()->back()->with('message', 'Login berhasil. Selamat datang, ' . $user->name . '!');
            } else {
                Auth::logout();
                return redirect()->route('login_page')->with('error', 'Login gagal, cek email dan password Anda.');
            }
        } else {
            RateLimiter::hit($key, 60);
            return redirect()->route('login_page')->with('error', 'Login gagal, cek email dan password Anda.');
        }
    }
}
