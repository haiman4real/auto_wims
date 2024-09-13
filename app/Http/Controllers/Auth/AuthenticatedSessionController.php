<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     Log::info('Authentication request incoming');

    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }
    public function store(LoginRequest $request): RedirectResponse
    {
        // Log the incoming authentication request
        $ipAddress = $request->ip();
        $email = $request->input('email');

        Log::info('Authentication request incoming', [
            'email' => $email,
            'ip_address' => $ipAddress,
        ]);

        // Check user status
        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::warning('Authentication failed: user not found', [
                'email' => $email,
                'ip_address' => $ipAddress,
            ]);
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        if ($user->user_status === 'blocked' || $user->user_status === 'disabled') {
            $statusMessage = $user->user_status === 'blocked' ? 'Your account is blocked due to multiple failed login attempts. Please contact the Admin.' : 'Your account is disabled. Please contact the Admin.';

            Log::warning('Authentication failed: user blocked or disabled', [
                'email' => $email,
                'ip_address' => $ipAddress,
            ]);
            throw ValidationException::withMessages([
                'email' => [$statusMessage],
            ]);
        }

        // Authenticate the user
        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            // Increment the login attempts
            $user->increment('login_attempts');

            if ($user->login_attempts >= 3) {
                // Block the user after 3 failed attempts
                $user->user_status = 'blocked';
                $user->save();

                Log::warning('User blocked after 3 failed login attempts', [
                    'email' => $email,
                    'ip_address' => $ipAddress,
                ]);

                throw ValidationException::withMessages([
                    'email' => ['Your account is blocked due to multiple failed login attempts. Please contact the Admin.'],
                ]);
            }

            throw $e;
        }

        // Reset login attempts on successful login
        $user->update(['login_attempts' => 0]);

        // Regenerate session
        $request->session()->regenerate();

        // Log the successful login
        Log::info('User logged in successfully', [
            'email' => $email,
            'ip_address' => $ipAddress,
        ]);

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
}
