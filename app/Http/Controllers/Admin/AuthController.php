<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session(['login_captcha_answer' => $num1 + $num2]);
        $captchaQuestion = "{$num1} + {$num2}";

        return view('admin.auth.login', compact('captchaQuestion'));
    }

    public function refreshCaptcha(): JsonResponse
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session(['login_captcha_answer' => $num1 + $num2]);

        return response()->json([
            'question' => "{$num1} + {$num2}",
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required|numeric',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'captcha.required' => 'Jawaban kode captcha keamanan wajib diisi.',
            'captcha.numeric' => 'Jawaban captcha harus berupa angka hasil perhitungan.',
        ]);

        $expectedCaptcha = session('login_captcha_answer');
        if (! $expectedCaptcha || (int) $request->captcha !== (int) $expectedCaptcha) {
            $num1 = rand(1, 9);
            $num2 = rand(1, 9);
            session(['login_captcha_answer' => $num1 + $num2]);

            return back()->withErrors([
                'captcha' => 'Jawaban captcha keamanan salah. Silakan hitung ulang.',
            ])->onlyInput('email');
        }

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->forget('login_captcha_answer');
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session(['login_captcha_answer' => $num1 + $num2]);

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
