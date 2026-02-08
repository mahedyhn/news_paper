<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

/**
 * SocialAuthController
 * 
 * Handles OAuth authentication with Google, Facebook, and GitHub.
 * This controller manages the OAuth flow including redirects and callbacks.
 */
class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth provider.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')
                ->scopes(['profile', 'email'])
                ->redirect();
        } catch (Exception $e) {
            Log::error('Google OAuth redirect failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Failed to initiate Google login');
        }
    }

    /**
     * Handle callback from Google OAuth.
     * 
     * This method is called after the user authorizes with Google.
     * It retrieves the user information and creates/updates the user record.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Try to find existing user by Google ID
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if (!$user) {
                // Check if user exists by email (in case they already registered)
                $user = User::where('email', $googleUser->getEmail())->first();
                
                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'oauth_provider' => 'google',
                        'email_verified_at' => now(), // Auto-verify email from OAuth
                        'password' => bcrypt(uniqid()), // Random password for OAuth users
                    ]);
                    
                    Log::info('New user created via Google OAuth: ' . $user->id);
                } else {
                    // Existing user, link Google account
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'oauth_provider' => 'google',
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                    
                    Log::info('Google account linked to existing user: ' . $user->id);
                }
            }
            
            // Update profile photo if available
            if ($googleUser->getAvatar()) {
                // You can save the avatar URL to a separate field or download it
                // For now, we'll just log it
                Log::info('Google Avatar available: ' . $googleUser->getAvatar());
            }
            
            // Log the user in
            Auth::login($user, true); // true = remember me
            
            return redirect('/dashboard')->with('success', 'Successfully logged in with Google!');
            
        } catch (Exception $e) {
            Log::error('Google OAuth callback failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Failed to authenticate with Google');
        }
    }

    /**
     * Redirect to Facebook OAuth provider.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToFacebook()
    {
        try {
            return Socialite::driver('facebook')
                ->scopes(['email', 'public_profile'])
                ->redirect();
        } catch (Exception $e) {
            Log::error('Facebook OAuth redirect failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Failed to initiate Facebook login');
        }
    }

    /**
     * Handle callback from Facebook OAuth.
     * 
     * This method is called after the user authorizes with Facebook.
     * It retrieves the user information and creates/updates the user record.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            // Try to find existing user by Facebook ID
            $user = User::where('facebook_id', $facebookUser->getId())->first();
            
            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $facebookUser->getEmail())->first();
                
                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $facebookUser->getName(),
                        'email' => $facebookUser->getEmail(),
                        'facebook_id' => $facebookUser->getId(),
                        'oauth_provider' => 'facebook',
                        'email_verified_at' => now(), // Auto-verify email from OAuth
                        'password' => bcrypt(uniqid()), // Random password for OAuth users
                    ]);
                    
                    Log::info('New user created via Facebook OAuth: ' . $user->id);
                } else {
                    // Existing user, link Facebook account
                    $user->update([
                        'facebook_id' => $facebookUser->getId(),
                        'oauth_provider' => 'facebook',
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                    
                    Log::info('Facebook account linked to existing user: ' . $user->id);
                }
            }
            
            // Update profile photo if available
            if ($facebookUser->getAvatar()) {
                Log::info('Facebook Avatar available: ' . $facebookUser->getAvatar());
            }
            
            // Log the user in
            Auth::login($user, true); // true = remember me
            
            return redirect('/dashboard')->with('success', 'Successfully logged in with Facebook!');
            
        } catch (Exception $e) {
            Log::error('Facebook OAuth callback failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Failed to authenticate with Facebook');
        }
    }

    /**
     * Redirect to GitHub OAuth provider (optional).
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGithub()
    {
        try {
            return Socialite::driver('github')->redirect();
        } catch (Exception $e) {
            Log::error('GitHub OAuth redirect failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Failed to initiate GitHub login');
        }
    }

    /**
     * Handle callback from GitHub OAuth.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            
            // Try to find existing user by GitHub ID
            $user = User::where('github_id', $githubUser->getId())->first();
            
            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $githubUser->getEmail())->first();
                
                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                        'email' => $githubUser->getEmail(),
                        'github_id' => $githubUser->getId(),
                        'oauth_provider' => 'github',
                        'email_verified_at' => now(),
                        'password' => bcrypt(uniqid()),
                    ]);
                    
                    Log::info('New user created via GitHub OAuth: ' . $user->id);
                } else {
                    // Existing user, link GitHub account
                    $user->update([
                        'github_id' => $githubUser->getId(),
                        'oauth_provider' => 'github',
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                    
                    Log::info('GitHub account linked to existing user: ' . $user->id);
                }
            }
            
            Auth::login($user, true);
            
            return redirect('/dashboard')->with('success', 'Successfully logged in with GitHub!');
            
        } catch (Exception $e) {
            Log::error('GitHub OAuth callback failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Failed to authenticate with GitHub');
        }
    }

    /**
     * Disconnect a social account from the user's profile.
     * 
     * Usage: POST /auth/disconnect/{provider}
     * Example: /auth/disconnect/google
     * 
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnectSocialAccount($provider)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect('/login');
            }
            
            // Validate provider
            $validProviders = ['google', 'facebook', 'github'];
            if (!in_array($provider, $validProviders)) {
                return back()->with('error', 'Invalid provider');
            }
            
            // Check if user has other authentication methods
            if ($user->password === null || $user->password === '') {
                return back()->with('error', 'You must set a password before disconnecting your social account');
            }
            
            // Get the ID field name for the provider
            $idField = $provider . '_id';
            
            // Disconnect the account
            $user->update([
                $idField => null,
            ]);
            
            Log::info('User ' . $user->id . ' disconnected ' . $provider . ' account');
            
            return back()->with('success', ucfirst($provider) . ' account successfully disconnected');
            
        } catch (Exception $e) {
            Log::error('Disconnect social account failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to disconnect account');
        }
    }
}
