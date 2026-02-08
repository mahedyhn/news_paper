# Social Authentication Implementation Checklist

## ✅ Phase 1: Setup (Files Created)

- [x] Migration: `database/migrations/2024_02_08_000000_add_social_login_to_users_table.php`
  - Adds google_id, facebook_id, github_id, oauth_provider columns
  
- [x] Controller: `app/Http/Controllers/SocialAuthController.php`
  - 6 methods for Google, Facebook, GitHub (redirect + callback)
  - Handles account creation and linking
  - Discord/GitHub support included

- [x] Routes: Routes registered in `routes/web.php`
  - Google: `/auth/google` and `/auth/google/callback`
  - Facebook: `/auth/facebook` and `/auth/facebook/callback`
  - GitHub: `/auth/github` and `/auth/github/callback`

- [x] Model: `app/Models/User.php` updated
  - Added fillable fields for social logins
  - Added email_verified_at

- [x] Templates:
  - Login example: `resources/views/auth/login-social-example.blade.php`
  - Register example: `resources/views/auth/register-social-example.blade.php`

- [x] Documentation:
  - Complete guide: `SOCIAL_AUTH_SETUP.md`
  - ENV template: `ENV_OAUTH_CONFIG.txt`

## ⏳ Phase 2: Get OAuth Credentials (Your To-Do)

### Google Setup
- [ ] Go to https://console.cloud.google.com/
- [ ] Create new project or select existing
- [ ] Enable "Google+ API"
- [ ] Go to Credentials
- [ ] Create "OAuth 2.0 Client ID" (Web application)
- [ ] Add Authorized JavaScript origins:
  - [ ] `http://localhost:8000`
  - [ ] `https://yourdomain.com`
- [ ] Add Authorized redirect URIs:
  - [ ] `http://localhost:8000/auth/google/callback`
  - [ ] `https://yourdomain.com/auth/google/callback`
- [ ] Copy Client ID → Add to `.env` as `GOOGLE_CLIENT_ID`
- [ ] Copy Client Secret → Add to `.env` as `GOOGLE_CLIENT_SECRET`

### Facebook Setup
- [ ] Go to https://developers.facebook.com/
- [ ] Go to My Apps
- [ ] Create new app
- [ ] App type: "Consumer"
- [ ] Add "Facebook Login" product
- [ ] Go to Settings > Basic
- [ ] Copy App ID → Add to `.env` as `FACEBOOK_CLIENT_ID`
- [ ] Copy App Secret → Add to `.env` as `FACEBOOK_CLIENT_SECRET`
- [ ] Go to Facebook Login > Settings
- [ ] Add Valid OAuth Redirect URIs:
  - [ ] `http://localhost:8000/auth/facebook/callback`
  - [ ] `https://yourdomain.com/auth/facebook/callback`

### GitHub Setup (Optional)
- [ ] Go to https://github.com/settings/developers
- [ ] Click "New OAuth App"
- [ ] Application name: "News Paper App"
- [ ] Authorization callback URL:
  - [ ] `http://localhost:8000/auth/github/callback`
  - [ ] `https://yourdomain.com/auth/github/callback`
- [ ] Copy Client ID → Add to `.env` as `GITHUB_CLIENT_ID`
- [ ] Copy Client Secret → Add to `.env` as `GITHUB_CLIENT_SECRET`

## ⏳ Phase 3: Update Environment Variables (Your To-Do)

- [ ] Open your `.env` file
- [ ] Add all Google OAuth variables
- [ ] Add all Facebook OAuth variables
- [ ] Add all GitHub OAuth variables (if using)
- [ ] Run: `php artisan config:clear` to reload config

## ⏳ Phase 4: Database Migration (Your To-Do)

- [ ] Run migration:
  ```bash
  php artisan migrate
  ```
- [ ] Verify new columns exist:
  ```bash
  mysql -u root -p your_database -e "DESCRIBE users;"
  ```
  - Should see: google_id, facebook_id, github_id, oauth_provider

## ⏳ Phase 5: Update Your Login/Register Pages (Your To-Do)

### Option A: Use Example Templates
- [ ] Copy content from `login-social-example.blade.php` to your login view
- [ ] Copy content from `register-social-example.blade.php` to your register view
- [ ] Update route names to match your auth routes
- [ ] Update error handling to match your error displays

### Option B: Manually Add Social Buttons
- [ ] Add to your login page:
  ```blade
  <a href="{{ route('auth.google') }}" class="btn-google">Login with Google</a>
  <a href="{{ route('auth.facebook') }}" class="btn-facebook">Login with Facebook</a>
  <a href="{{ route('auth.github') }}" class="btn-github">Login with GitHub</a>
  ```

## ⏳ Phase 6: Test Local OAuth Flow (Your To-Do)

1. [ ] Start your development server:
   ```bash
   php artisan serve
   ```

2. [ ] Test Google Login:
   - [ ] Go to: `http://localhost:8000/auth/google`
   - [ ] Authorize with Google account
   - [ ] Check if redirected to dashboard
   - [ ] Check if user created in database

3. [ ] Test Facebook Login:
   - [ ] Go to: `http://localhost:8000/auth/facebook`
   - [ ] Authorize with Facebook account
   - [ ] Check if redirected to dashboard

4. [ ] Test Account Linking:
   - [ ] Register with email
   - [ ] Login with Google
   - [ ] Go to profile
   - [ ] Verify both accounts are linked

5. [ ] Check application log:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## ⏳ Phase 7: Production Deployment (Your To-Do)

- [ ] Update `.env` with production URLs (https://)
- [ ] Update OAuth app settings with production URLs
- [ ] Update redirect URIs in Google Console
- [ ] Update redirect URIs in Facebook App
- [ ] Update redirect URIs in GitHub OAuth App
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Test OAuth flow on production domain
- [ ] Monitor `storage/logs/laravel.log` for errors

## ⏳ Phase 8: Optional Enhancements (Your To-Do)

- [ ] Add user profile photo from OAuth provider
- [ ] Add OAuth account disconnect from user profile page
- [ ] Add email verification for non-OAuth users
- [ ] Add rate limiting to OAuth endpoints:
  ```php
  Route::get('/auth/google', [...])
      ->middleware('throttle:5,1')
  ```
- [ ] Add logging for security monitoring
- [ ] Add custom error pages for OAuth failures

## ⏳ Phase 9: Security Hardening (Your To-Do)

- [ ] Enable HTTPS on all environments
- [ ] Use strong database passwords
- [ ] Implement CSRF protection (automatic with Laravel)
- [ ] Add rate limiting to prevent brute force:
  ```php
  Route::post('/login', [...])
      ->middleware('throttle:5,1')
  ```
- [ ] Monitor and log OAuth failures
- [ ] Implement email verification for security
- [ ] Add two-factor authentication (future enhancement)
- [ ] Regular security audits

## 🔍 Troubleshooting Guides

### "Invalid client ID" Error
1. [ ] Check `.env` file for typos
2. [ ] Verify credentials in Google/Facebook console
3. [ ] Run `php artisan config:clear`
4. [ ] Restart development server

### "Redirect URI mismatch"
1. [ ] Check exact OAuth URL format (http vs https)
2. [ ] Verify trailing slashes match
3. [ ] Check for query parameters
4. [ ] Update in both .env AND provider console

### User Not Created
1. [ ] Check migration ran: `php artisan migrate:status`
2. [ ] Check database columns: `DESCRIBE users;`
3. [ ] Check application logs: `tail -f storage/logs/laravel.log`

### Email Not Returned from Provider
1. [ ] Check scopes in SocialAuthController
2. [ ] Verify email in OAuth provider account
3. [ ] Check provider privacy settings
4. [ ] Use test account with public email

## 📚 Files Reference

| File | Purpose |
|------|---------|
| `SocialAuthController.php` | OAuth logic (redirects, callbacks, user creation) |
| `routes/web.php` | OAuth routes for each provider |
| `User.php` | User model with social fields |
| `2024_02_08_000000_add_social_login_to_users_table.php` | Database migration |
| `login-social-example.blade.php` | Example login page with OAuth buttons |
| `register-social-example.blade.php` | Example register page with OAuth buttons |
| `SOCIAL_AUTH_SETUP.md` | Complete documentation |
| `ENV_OAUTH_CONFIG.txt` | .env configuration template |

## 🔗 Useful Commands

```bash
# Clear config cache
php artisan config:clear

# Run migrations
php artisan migrate

# Check migration status
php artisan migrate:status

# Tinker to check users
php artisan tinker
>>> DB::table('users')->first()
>>> exit

# View logs
tail -f storage/logs/laravel.log

# Clear all cache
php artisan cache:clear
```

## 📞 Support Resources

- [Laravel Socialite Docs](https://laravel.com/docs/10.x/socialite)
- [Google OAuth Setup Guide](https://developers.google.com/identity/protocols/oauth2)
- [Facebook Login Guide](https://developers.facebook.com/docs/facebook-login)
- [GitHub OAuth Setup](https://docs.github.com/en/developers/apps/building-oauth-apps)

---

## Summary of Routes Created

```
GET  /auth/google              → Redirect to Google
GET  /auth/google/callback     → Handle Google callback
GET  /auth/facebook            → Redirect to Facebook
GET  /auth/facebook/callback   → Handle Facebook callback
GET  /auth/github              → Redirect to GitHub
GET  /auth/github/callback     → Handle GitHub callback
```

---

## Database Schema Changes

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

**Status: Ready to configure OAuth credentials and begin testing!**
