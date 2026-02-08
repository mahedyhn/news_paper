# Complete Social Authentication Setup Guide for Laravel

## Table of Contents
1. [Overview](#overview)
2. [Required Packages](#required-packages)
3. [Environment Configuration](#environment-configuration)
4. [Google OAuth Setup](#google-oauth-setup)
5. [Facebook OAuth Setup](#facebook-oauth-setup)
6. [Implementation Files](#implementation-files)
7. [Running Migrations](#running-migrations)
8. [Frontend Integration](#frontend-integration)
9. [Security Best Practices](#security-best-practices)
10. [Testing](#testing)
11. [Troubleshooting](#troubleshooting)

---

## Overview

This guide implements a complete OAuth authentication system with Google, Facebook, and GitHub. Users can:
- Register and login with email/password
- Login with Google account
- Login with Facebook account
- Login with GitHub account (bonus)
- Link multiple OAuth providers to one account

**Architecture:**
- Uses Laravel Socialite for OAuth handling
- Stores OAuth IDs in the users table
- Auto-verifies emails from OAuth providers
- Logs all authentication events
- Supports account linking (connect multiple providers)

---

## Required Packages

✅ **Laravel Socialite is already installed in your project!**

Verify by checking `composer.json`:
```json
"laravel/socialite": "^5.6"
```

If not installed, run:
```bash
composer require laravel/socialite
```

---

## Environment Configuration

### 1. Update Your `.env` File

Add these configuration variables to your `.env` file:

```env
# ============================================
# GOOGLE OAUTH CONFIGURATION
# ============================================
GOOGLE_CLIENT_ID=your_google_client_id_here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# ============================================
# FACEBOOK OAUTH CONFIGURATION
# ============================================
FACEBOOK_CLIENT_ID=your_facebook_app_id_here
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret_here
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback

# ============================================
# GITHUB OAUTH CONFIGURATION (Optional)
# ============================================
GITHUB_CLIENT_ID=your_github_client_id_here
GITHUB_CLIENT_SECRET=your_github_client_secret_here
GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
```

**Important URLs (Production):**
- Replace `http://localhost:8000` with your actual domain
- Example: `https://yourdomain.com/auth/google/callback`

### 2. Register OAuth Providers in config/services.php

The `config/services.php` file should already have placeholders. Update it:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],

'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
],

'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect' => env('GITHUB_REDIRECT_URI'),
],
```

---

## Google OAuth Setup

### Step 1: Go to Google Cloud Console

1. Visit: https://console.cloud.google.com/
2. Sign in with your Google account
3. Click "Select a Project" → "New Project"
4. Project Name: "News Paper App"
5. Click "Create"

### Step 2: Enable Google+ API

1. Go to "APIs & Services" → "Library"
2. Search for "Google+ API"
3. Click on it and press "Enable"

### Step 3: Create OAuth 2.0 Credentials

1. Go to "APIs & Services" → "Credentials"
2. Click "Create Credentials" → "OAuth 2.0 Client ID"
3. If prompted, first set up the OAuth consent screen:
   - User Type: External
   - Fill in App name: "News Paper"
   - User support email: your-email@example.com
   - Developer contact: your-email@example.com
   - Save and continue

4. After OAuth consent screen setup:
   - Application type: "Web application"
   - Name: "News Paper App"
   - Authorized JavaScript origins:
     - `http://localhost:8000`
     - `https://yourdomain.com` (production)
   - Authorized redirect URIs:
     - `http://localhost:8000/auth/google/callback`
     - `https://yourdomain.com/auth/google/callback` (production)

5. Click "Create"

### Step 4: Copy Credentials

1. A dialog appears with credentials
2. Copy the "Client ID" → Add to `.env` as `GOOGLE_CLIENT_ID`
3. Copy the "Client Secret" → Add to `.env` as `GOOGLE_CLIENT_SECRET`

---

## Facebook OAuth Setup

### Step 1: Go to Facebook Developers

1. Visit: https://developers.facebook.com/
2. Sign in or create account
3. Click "My Apps" → "Create App"

### Step 2: Create App

1. App Name: "News Paper"
2. App Purpose: Select appropriate category
3. Click "Create App"
4. Choose "Consumer" as app type

### Step 3: Add Facebook Login Product

1. Go to "Products" → Add "Facebook Login"
2. Choose "Web"
3. Site URL: `http://localhost:8000` or your domain

### Step 4: Configure Valid OAuth Redirect URIs

1. Go to "Settings" → "Basic"
2. Copy the "App ID" → Add to `.env` as `FACEBOOK_CLIENT_ID`
3. Go to "Settings" → "Basic", scroll down
4. Copy "App Secret" → Add to `.env` as `FACEBOOK_CLIENT_SECRET`
5. Go to "Facebook Login" → "Settings"
6. Valid OAuth Redirect URIs:
   - `http://localhost:8000/auth/facebook/callback`
   - `https://yourdomain.com/auth/facebook/callback` (production)
7. Click "Save Changes"

### Step 5: Request Permissions

1. Go to "Roles" → "Test Users"
2. Or use your own account for testing
3. Make sure your app is in "Development" mode for testing

---

## Implementation Files

### Files Created/Modified:

#### 1. **Migration** - `database/migrations/2024_02_08_000000_add_social_login_to_users_table.php`
   - Adds social login columns to users table
   - Columns: `google_id`, `facebook_id`, `github_id`, `oauth_provider`
   - Includes database indexes for performance

#### 2. **User Model** - `app/Models/User.php`
   - Updated fillable array with social ID fields
   - Add email_verified_at to auto-verify OAuth users

#### 3. **SocialAuthController** - `app/Http/Controllers/SocialAuthController.php`
   - Handles OAuth redirects and callbacks
   - Creates/links users with OAuth accounts
   - Supports disconnecting social accounts

#### 4. **Routes** - `routes/web.php`
   - Added 6 OAuth routes (3 providers × 2 = redirect + callback)
   - Routes use named routes for easy referencing

---

## Running Migrations

### Step 1: Run the New Migration

```bash
php artisan migrate
```

This will:
- Add social ID columns to users table
- Create database indexes for OAuth lookups

### Step 2: Verify Database Changes

```bash
mysql -u root -p news_paper -e "DESCRIBE users;"
```

You should see new columns:
- `google_id`
- `facebook_id`
- `github_id`
- `oauth_provider`

---

## Frontend Integration

### Example: Add Login Buttons to Your Login Page

Create `resources/views/auth/login.blade.php`:

```blade
<div class="login-container">
    <h2>Login to News Paper</h2>
    
    <!-- Email/Password Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login with Email</button>
    </form>
    
    <div class="divider">OR</div>
    
    <!-- Social Login Buttons -->
    <div class="social-login">
        <a href="{{ route('auth.google') }}" class="btn btn-google">
            <i class="fab fa-google"></i> Login with Google
        </a>
        
        <a href="{{ route('auth.facebook') }}" class="btn btn-facebook">
            <i class="fab fa-facebook"></i> Login with Facebook
        </a>
        
        <a href="{{ route('auth.github') }}" class="btn btn-github">
            <i class="fab fa-github"></i> Login with GitHub
        </a>
    </div>
</div>

<style>
.social-login {
    display: grid;
    gap: 10px;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    border-radius: 5px;
    text-align: center;
    text-decoration: none;
    color: white;
    font-weight: bold;
    transition: transform 0.2s;
}

.btn:hover {
    transform: scale(1.05);
}

.btn-google {
    background-color: #4285F4;
}

.btn-facebook {
    background-color: #1877F2;
}

.btn-github {
    background-color: #333;
}
</style>
```

### Example: Link Social Accounts in User Profile

```blade
<!-- User Profile Page -->
<div class="connected-accounts">
    <h3>Connected Accounts</h3>
    
    @if(auth()->user()->google_id)
        <p>✓ Google connected</p>
        <form method="POST" action="{{ route('auth.disconnect', 'google') }}">
            @csrf
            <button type="submit">Disconnect Google</button>
        </form>
    @else
        <a href="{{ route('auth.google') }}">Connect Google</a>
    @endif
    
    @if(auth()->user()->facebook_id)
        <p>✓ Facebook connected</p>
        <form method="POST" action="{{ route('auth.disconnect', 'facebook') }}">
            @csrf
            <button type="submit">Disconnect Facebook</button>
        </form>
    @else
        <a href="{{ route('auth.facebook') }}">Connect Facebook</a>
    @endif
</div>
```

---

## Security Best Practices

### 1. **Environment Variables**
   ✅ Never hardcode OAuth credentials
   ✅ Use `.env` file for secrets
   ✅ Add `.env` to `.gitignore`
   ✅ Keep `.env.example` in version control without secrets

### 2. **HTTPS in Production**
   ✅ Always use HTTPS for OAuth callbacks
   ✅ Update callback URLs to HTTPS in dev console
   ✅ Use SSL certificates (Let's Encrypt is free)

### 3. **Password Handling**
   ✅ Generate random passwords for OAuth users
   ✅ Require password setup before disconnecting social accounts
   ✅ Use `bcrypt()` to hash passwords

### 4. **Email Verification**
   ✅ Auto-verify emails from OAuth providers
   ✅ OAuth providers guarantee email ownership
   ✅ Set `email_verified_at` on OAuth registration

### 5. **Account Linking**
   ✅ Check for existing users by email before creating new ones
   ✅ Allow users to link multiple OAuth providers
   ✅ Prevent accidental account duplication

### 6. **Logging & Monitoring**
   ✅ Log all OAuth events (success/failure)
   ✅ Monitor failed authentication attempts
   ✅ Alert on suspicious activity

### 7. **Validation**
   ✅ Validate all OAuth provider data
   ✅ Check token expiration
   ✅ Handle network errors gracefully

### 8. **Rate Limiting**
   ```php
   // In routes/web.php
   Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])
       ->middleware('throttle:5,1') // 5 attempts per minute
       ->name('auth.google');
   ```

### 9. **CSRF Protection**
   ✅ Laravel Socialite handles CSRF protection automatically
   ✅ No need to add extra CSRF tokens

### 10. **Data Privacy**
   ✅ Only request necessary permissions (profile, email)
   ✅ Don't store sensitive data (like tokens)
   ✅ Inform users about data collection (Privacy Policy)

---

## Testing

### Test Google Login

1. Start your development server:
   ```bash
   php artisan serve
   ```

2. Go to: `http://localhost:8000/auth/google`

3. Approve permissions when prompted

4. You should be logged in and redirected to dashboard

### Test Facebook Login

1. Go to: `http://localhost:8000/auth/facebook`

2. Approve permissions when prompted

3. You should be logged in and redirected to dashboard

### Test Account Linking

1. Register with email
2. Go to profile/settings
3. Click "Connect Google"
4. Login with Google
5. Account should be linked

### Test with Test Users (Facebook)

Use Facebook test users:
1. Facebook App → Roles → Test Users
2. Create a test user
3. Use those credentials to test

---

## Troubleshooting

### Issue: "Invalid client ID" Error

**Solution:**
1. Verify `.env` file has correct IDs
2. Check Google Cloud Console for typos
3. Ensure credentials are not expired
4. Clear Laravel cache: `php artisan config:clear`

### Issue: "Redirect URI mismatch"

**Solution:**
1. Verify callback URL matches exactly in configuration
2. Check for trailing slashes mismatch
3. Ensure https:// vs http:// is consistent
4. Check in both dev console and `.env` file

### Issue: Email Not Returned from Provider

**Solution:**
```php
// In SocialAuthController
$facebookUser = Socialite::driver('facebook')
    ->scopes(['email', 'public_profile'])
    ->fields(['id', 'name', 'email', 'picture'])
    ->user();
```

### Issue: User Created Every Time Instead of Linking

**Solution:**
Ensure database lookup happens correctly:
```php
// Migrate database first
php artisan migrate

// Verify social ID columns exist
php artisan tinker
>>> DB::table('users')->first()->google_id
```

### Issue: Token Expired Errors

**Solution:**
1. Clear browser cookies and cache
2. Generate new OAuth credentials
3. Restart development server
4. Use incognito/private browser window

### Issue: "The given data was invalid" (422 error)

**Solution:**
1. Check all fillable fields in User model
2. Verify migration ran successfully
3. Check for unique email constraint
4. Enable Laravel logging to see full error

### Checking Logs

View authentication details:
```bash
tail -f storage/logs/laravel.log

# Or in Laravel Tinker
php artisan tinker
>>> \Illuminate\Support\Facades\Log::info('Test message');
>>> exit
```

---

## API Integration (If Using with Frontend API)

If building a separate frontend (React, Vue, etc.), create API endpoints:

```php
// routes/api.php
Route::post('/auth/social-register', [ApiSocialAuthController::class, 'register']);
Route::post('/auth/social-login', [ApiSocialAuthController::class, 'login']);
```

You can then use the API_SERVICE.js file with these endpoints.

---

## Summary of Credentials Needed

| Provider | Credential | Where to Get |
|----------|-----------|-------------|
| Google | Client ID | Google Cloud Console → Credentials |
| Google | Client Secret | Google Cloud Console → Credentials |
| Facebook | App ID | Facebook App → Settings → Basic |
| Facebook | App Secret | Facebook App → Settings → Basic |
| GitHub | Client ID | GitHub → Settings → Developer settings → OAuth Apps |
| GitHub | Client Secret | GitHub → Settings → Developer settings → OAuth Apps |

---

## Database Schema

The modified users table now has:

```sql
ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN facebook_id VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN github_id VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN oauth_provider VARCHAR(255) NULL;

CREATE INDEX idx_google_id ON users(google_id);
CREATE INDEX idx_facebook_id ON users(facebook_id);
CREATE INDEX idx_github_id ON users(github_id);
CREATE INDEX idx_oauth_provider ON users(oauth_provider);
```

---

## Next Steps

1. **Obtain OAuth credentials** from Google and Facebook
2. **Update .env file** with your credentials
3. **Run migration**: `php artisan migrate`
4. **Test OAuth flow** with the URLs provided
5. **Create custom login UI** using blade templates
6. **Monitor logs** for any issues
7. **Deploy to production** with HTTPS

---

## Additional Resources

- [Laravel Socialite Documentation](https://laravel.com/docs/10.x/socialite)
- [Google OAuth 2.0 Setup](https://developers.google.com/identity/protocols/oauth2)
- [Facebook Login Documentation](https://developers.facebook.com/docs/facebook-login)
- [GitHub OAuth Setup](https://docs.github.com/en/developers/apps/building-oauth-apps)

---

**Questions or Issues?**
Check the Troubleshooting section above or review the SocialAuthController for detailed implementation.
