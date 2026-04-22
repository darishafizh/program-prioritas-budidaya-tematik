<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $this->generateCaptcha($request);

        return view('auth.login', [
            'captcha_question' => $request->session()->get('captcha_question'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validate CAPTCHA first
        $request->validate([
            'captcha' => ['required'],
        ], [
            'captcha.required' => 'Jawaban CAPTCHA wajib diisi.',
        ]);

        $captchaAnswer = $request->session()->get('captcha_answer');

        if ((int) $request->input('captcha') !== $captchaAnswer) {
            // Regenerate captcha for next attempt
            $this->generateCaptcha($request);

            return back()->withErrors([
                'captcha' => 'Jawaban CAPTCHA salah. Silakan coba lagi.',
            ])->withInput($request->only('username', 'remember'));
        }

        $request->authenticate();

        $request->session()->regenerate();

        // Clean up captcha session data
        $request->session()->forget(['captcha_question', 'captcha_answer']);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Generate a simple math CAPTCHA and store in session.
     */
    private function generateCaptcha(Request $request): void
    {
        $operators = ['+', '-', '×'];
        $operator = $operators[array_rand($operators)];

        switch ($operator) {
            case '+':
                $a = rand(1, 20);
                $b = rand(1, 20);
                $answer = $a + $b;
                break;
            case '-':
                $a = rand(5, 25);
                $b = rand(1, $a); // ensure non-negative result
                $answer = $a - $b;
                break;
            case '×':
                $a = rand(2, 9);
                $b = rand(2, 9);
                $answer = $a * $b;
                break;
        }

        $question = "{$a} {$operator} {$b} = ?";

        $request->session()->put('captcha_question', $question);
        $request->session()->put('captcha_answer', $answer);
    }
}
