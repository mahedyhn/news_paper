<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

/**
 * AuthenticationController
 * 
 * Handles traditional email/password authentication
 * (login, register, logout functionality)
 */
class AuthenticationController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login-custom');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        try {
            // Validate input
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ], [
                'email.required' => 'Email is required',
                'email.email' => 'Email must be a valid email address',
                'password.required' => 'Password is required',
            ]);

            // Track login attempts for security
            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                Log::warning('Failed login attempt for email: ' . $credentials['email']);
                
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'These credentials do not match our records.',
                    ]);
            }

            // Check if email is verified
            if (!$user->email_verified_at && !$user->google_id && !$user->facebook_id && !$user->github_id) {
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Please verify your email address before logging in.');
            }

            // Log the user in
            Auth::login($user, $request->has('remember'));

            Log::info('User logged in successfully: ' . $user->id);

            // Regenerate session for security
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during login. Please try again.');
        }
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register-custom');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed',
                    PasswordRule::min(8)
                        ->mixedCase()
                        ->numbers()
                ],
                'terms' => ['required', 'accepted'],
            ], [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Email must be a valid email address',
                'email.unique' => 'This email is already registered',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 8 characters',
                'password.confirmed' => 'Passwords do not match',
                'terms.required' => 'You must agree to the terms and conditions',
                'terms.accepted' => 'You must accept the terms and conditions',
            ]);

            // Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(), // Auto-verify password-based registrations
            ]);

            Log::info('New user registered: ' . $user->id . ' (' . $user->email . ')');

            // Log the user in
            Auth::login($user);

            return redirect('/dashboard')
                ->with('success', 'Welcome, ' . $user->name . '! Your account has been created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput($request->only('name', 'email'))
                ->withErrors($e->errors());
                
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return back()
                ->withInput($request->only('name', 'email'))
                ->with('error', 'An error occurred during registration. Please try again.');
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('User logged out');

            return redirect('/')->with('success', 'You have been logged out successfully.');

        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return redirect('/')->with('error', 'An error occurred during logout.');
        }
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset email request
     */
    public function sendResetLink(Request $request)
    {
        try {
            // Validate email
            $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
            ], [
                'email.required' => 'Email is required',
                'email.email' => 'Email must be a valid email address',
                'email.exists' => 'We could not find an account with that email address',
            ]);

            // Send the password reset link
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                Log::info('Password reset link sent to: ' . $request->email);
                
                return back()->with('success', 'Password reset link has been sent to your email address. Please check your inbox.');
            }

            Log::warning('Failed to send password reset link for: ' . $request->email);

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'An error occurred while sending the reset link. Please try again.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors($e->errors());
                
        } catch (\Exception $e) {
            Log::error('Password reset link error: ' . $e->getMessage());
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'An error occurred. Please try again later.');
        }
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle password reset request
     */
    public function resetPassword(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email', 'exists:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed',
                    PasswordRule::min(8)
                        ->mixedCase()
                        ->numbers()
                ],
            ], [
                'email.required' => 'Email is required',
                'email.email' => 'Email must be a valid email address',
                'email.exists' => 'We could not find an account with that email address',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 8 characters',
                'password.confirmed' => 'Passwords do not match',
            ]);

            // Reset the password
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();

                    $user->setRememberToken(\Illuminate\Support\Str::random(60));

                    Log::info('Password reset for user: ' . $user->id);
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect('/login')->with('success', 'Your password has been reset successfully. Please log in with your new password.');
            }

            Log::warning('Password reset failed for email: ' . $request->email);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors($e->errors());
                
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'An error occurred while resetting your password.');
        }
    }
}
