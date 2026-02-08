# Social OAuth - Quick Reference Guide

## 🚀 Quick Start

### 1. Get Credentials (5 minutes each)

**Google:**
1. Go: https://console.cloud.google.com/
2. Create OAuth Client ID (Web)
3. Copy credentials

**Facebook:**
1. Go: https://developers.facebook.com/
2. Create new app
3. Add Facebook Login product
4. Copy credentials

### 2. Update .env (30 seconds)
```env
GOOGLE_CLIENT_ID=xxx
GOOGLE_CLIENT_SECRET=yyy
FACEBOOK_CLIENT_ID=aaa
FACEBOOK_CLIENT_SECRET=bbb
```

### 3. Run Migration (10 seconds)
```bash
php artisan migrate
```

### 4. Test (2 minutes)
```
http://localhost:8000/auth/google
http://localhost:8000/auth/facebook
```

## 📋 Files You Need to Know

| File | What It Does |
|------|-------------|
| `SocialAuthController.php` | Handles all OAuth logic |
| `routes/web.php` | OAuth routes |
| `User.php` | Stores google_id, facebook_id, etc |

## 🔧 Controller Methods

### Google Login Flow
```
User clicks "Login with Google"
  ↓
redirectToGoogle() → Redirect to Google login
  ↓
User authorizes
  ↓
handleGoogleCallback() → Creates/updates user
  ↓
Redirect to dashboard
```

### Facebook Login Flow
```
User clicks "Login with Facebook"
  ↓
redirectToFacebook() → Redirect to Facebook login
  ↓
User authorizes
  ↓
handleFacebookCallback() → Creates/updates user
  ↓
Redirect to dashboard
```

## 🎯 Common Tasks

### Add Login Button to Your Page
```blade
<a href="{{ route('auth.google') }}" class="btn">Login with Google</a>
<a href="{{ route('auth.facebook') }}" class="btn">Login with Facebook</a>
```

### Check User's OAuth Accounts
```php
$user = Auth::user();

if ($user->google_id) {
    echo "Google connected";
}

if ($user->facebook_id) {
    echo "Facebook connected";
}
```

### Get All OAuth Users
```php
// All Google users
$googleUsers = User::whereNotNull('google_id')->get();

// All Facebook users
$facebookUsers = User::whereNotNull('facebook_id')->get();
```

## 🐛 Common Errors

| Error | Fix |
|-------|-----|
| Invalid client ID | Check `.env` file for typos |
| Redirect URI mismatch | Verify exact URL in OAuth provider |
| User not created | Run `php artisan migrate` |
| Token not recognized | Clear cache: `php artisan config:clear` |
| Email not returned | Check OAuth scopes in controller |

## 🔐 Security Reminders

✅ Always use HTTPS in production
✅ Keep credentials in .env (not .js or .html)
✅ Never commit .env to Git
✅ Use unique, random passwords for OAuth users
✅ Auto-verify emails from OAuth providers
✅ Log all authentication events

## 📊 Database Queries

### Create user via OAuth
```php
User::create([
    'name' => $googleUser->getName(),
    'email' => $googleUser->getEmail(),
    'google_id' => $googleUser->getId(),
    'email_verified_at' => now(),
    'password' => bcrypt(uniqid()),
]);
```

### Find user by Google ID
```php
User::where('google_id', $googleId)->first();
```

### Find user by Facebook ID
```php
User::where('facebook_id', $facebookId)->first();
```

## 🧪 Testing

```bash
# Test Google login
http://localhost:8000/auth/google

# Test Facebook login
http://localhost:8000/auth/facebook

# Check users in database
php artisan tinker
>>> User::all()
>>> exit

# View logs
tail -f storage/logs/laravel.log
```

## 🔌 API Integration

If building a separate frontend (React, Vue), you can create API endpoints:

```php
// routes/api.php
Route::post('/auth/google/callback', [ApiSocialAuthController::class, 'googleCallback']);
Route::post('/auth/facebook/callback', [ApiSocialAuthController::class, 'facebookCallback']);
```

Then return tokens:
```php
$token = $user->createToken('api-token')->plainTextToken;
return response()->json(['token' => $token]);
```

## 📱 Frontend Examples

### React/Vue
```javascript
// Redirect to Google
window.location.href = '/auth/google';

// Or from your API service
authService.loginWithGoogle();
```

### Pure HTML
```html
<a href="/auth/google" class="btn-google">Login with Google</a>
```

## 🔄 OAuth Flow Diagram

```
┌─────────────────────────────────┐
│   User clicks Social Button     │
└────────┬────────────────────────┘
         │
         ↓
┌─────────────────────────────────┐
│ redirectToGoogle/Facebook()     │
│ Takes user to OAuth provider    │
└────────┬────────────────────────┘
         │
         ↓
┌─────────────────────────────────┐
│ User authorizes on Google/FB    │
│ Gets redirected back to us      │
└────────┬────────────────────────┘
         │
         ↓
┌─────────────────────────────────┐
│ handle*Callback()               │
│ Gets OAuth user data            │
└────────┬────────────────────────┘
         │
         ↓
┌─────────────────────────────────┐
│ Check if user exists            │
│ Create/Update user              │
└────────┬────────────────────────┘
         │
         ↓
┌─────────────────────────────────┐
│ Log user in                     │
│ Redirect to dashboard           │
└─────────────────────────────────┘
```

## 🎓 Learning Path

1. ✅ Understand OAuth 2.0 concept
2. ✅ Get credentials from Google/Facebook
3. ✅ Add .env variables
4. ✅ Run migration
5. ✅ Test login flow
6. ✅ Add UI buttons
7. ✅ Test account linking
8. ✅ Deploy to production

## 📞 If Something Breaks

1. Check `storage/logs/laravel.log`
2. Verify .env variables
3. Verify migration ran
4. Clear cache: `php artisan config:clear`
5. Check browser console for errors
6. Use `php artisan tinker` to debug

## ✨ Pro Tips

- Use test OAuth accounts while developing
- Store OAuth provider avatar URLs separately if needed
- Log all authentication events for monitoring
- Implement email verification for non-OAuth users
- Add rate limiting to prevent abuse
- Monitor OAuth failure rates

## 🚀 Production Checklist

- [ ] Update .env with production URLs (https://)
- [ ] Update OAuth redirect URIs to production domain
- [ ] Enable HTTPS
- [ ] Set APP_DEBUG=false
- [ ] Monitor logs for errors
- [ ] Test OAuth flow on production
- [ ] Set up error monitoring (Sentry, etc)

---

**Need more help? Check SOCIAL_AUTH_SETUP.md for detailed docs**
