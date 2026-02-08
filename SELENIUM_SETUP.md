# Selenium Authentication Tests Setup Guide

## Overview
This guide helps you set up and run Selenium browser automation tests for your authentication system.

## Prerequisites

1. **PHP** (7.4 or higher)
2. **Composer** (for PHP dependencies)
3. **Chrome/Chromium browser** (installed on your system)
4. **ChromeDriver** (must match your Chrome version)
5. **Java** (optional, if using Selenium Server)

## Installation Steps

### Step 1: Install WebDriver PHP Library

```bash
composer require facebook/webdriver
```

### Step 2: Download ChromeDriver

1. Check your Chrome version:
   - Open Chrome → Menu (⋮) → Help → About Google Chrome
   - Note the version number

2. Download ChromeDriver:
   - Visit https://chromedriver.chromium.org/
   - Download the version that matches your Chrome version
   - Extract the `chromedriver.exe` file to a known location

3. On Windows, add chromedriver to PATH or note its full path

### Step 3: Update .env File

Add or update these variables in your `.env` file:

```env
APP_URL=http://localhost

# Test user credentials
TEST_USER_EMAIL=test@example.com
TEST_USER_PASSWORD=password

# Optional: Enable headless mode
SELENIUM_HEADLESS=false
```

### Step 4: Create Test User

Create a test user in your database with these credentials:

**Option A: Using Tinker**
```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);
```

**Option B: Using Seeding**

Update `database/seeders/DatabaseSeeder.php`:

```php
public function run()
{
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'name' => 'Test User',
    ]);
}
```

Then run:
```bash
php artisan db:seed
```

## Running the Tests

### Step 1: Start Your Application

Make sure your Laravel application is running:

```bash
php artisan serve
```

It should be accessible at `http://localhost:8000` (or your configured URL)

### Step 2: Start ChromeDriver

Open a new terminal/command prompt and run:

**Windows:**
```bash
C:\path\to\chromedriver.exe
```

**macOS/Linux:**
```bash
./chromedriver
```

You should see output like:
```
Starting ChromeDriver 120.0.6099.129 on port 9515
```

### Step 3: Run the Tests

In another terminal, run:

**All Selenium tests:**
```bash
./vendor/bin/phpunit tests/Selenium
```

**Specific test file:**
```bash
./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php
```

**Specific test method:**
```bash
./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php --filter test_user_can_see_login_form
```

**With verbose output:**
```bash
./vendor/bin/phpunit tests/Selenium --verbose
```

## Test Cases Included

### 1. **Form Visibility Tests**
- ✅ User can see login form
- ✅ User can see register form

### 2. **Login Tests**
- ✅ User can login with valid credentials
- ✅ Login fails with invalid email
- ✅ Login fails with invalid password
- ✅ Login form displays validation errors
- ✅ Password field is masked
- ✅ Form has CSRF token

### 3. **Registration Tests**
- ✅ User can register with valid data
- ✅ Registration fails with mismatched passwords
- ✅ Registration fails with existing email

### 4. **Session & Authorization Tests**
- ✅ Authenticated user cannot access login page
- ✅ Unauthenticated user cannot access dashboard
- ✅ Authenticated user can logout

### 5. **Social Login Tests**
- ✅ Social login buttons are visible

## Troubleshooting

### ChromeDriver Connection Failed
**Problem:** "Could not connect to localhost:9515"

**Solutions:**
1. Make sure ChromeDriver is running on port 9515
2. Check if port 9515 is not blocked by firewall
3. Verify ChromeDriver path and permissions

### Tests Timeout
**Problem:** Tests are timing out

**Solutions:**
1. Increase wait timeout in `SeleniumTestCase.php`:
   ```php
   protected int $defaultTimeout = 20; // Increase from 10
   ```
2. Make sure application is running and accessible
3. Check network connectivity

### Chrome Not Found
**Problem:** Chrome browser not found

**Solutions:**
1. Install Chrome from https://www.google.com/chrome/
2. Verify Chrome installation path
3. Update ChromeDriver version to match Chrome

### Test User Not Found
**Problem:** "Error: nonexistent user"

**Solutions:**
1. Create test user using Tinker or Seeding (see Installation Step 4)
2. Verify database is migrated: `php artisan migrate`
3. Check database connection in `.env`

## Advanced Configuration

### Enable Headless Mode

Edit `SeleniumTestCase.php`:

```php
$options->addArguments(['headless', 'disable-gpu']);
```

Or use environment variable:

```env
SELENIUM_HEADLESS=true
```

### Custom Viewport Size

Modify in `SeleniumTestCase.php`:

```php
$options->addArguments(['window-size=1280,720']);
```

### Take Screenshots on Failure

Add method to `SeleniumTestCase.php`:

```php
protected function onNotSuccessfulTest(\Throwable $t): void
{
    $filename = 'tests/Selenium/screenshots/' . time() . '.png';
    $this->driver->takeScreenshot($filename);
    parent::onNotSuccessfulTest($t);
}
```

Create `tests/Selenium/screenshots/` directory.

## CI/CD Integration

### GitHub Actions Example

Create `.github/workflows/selenium.yml`:

```yaml
name: Selenium Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v2
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      
      - name: Install dependencies
        run: composer install
      
      - name: Start application
        run: php artisan serve &
      
      - name: Install Chrome
        uses: browser-actions/setup-chrome@latest
      
      - name: Setup ChromeDriver
        uses: nanasess/setup-chromedriver@master
      
      - name: Start ChromeDriver
        run: chromedriver &
      
      - name: Run Selenium Tests
        run: ./vendor/bin/phpunit tests/Selenium
```

## Performance Tips

1. **Run tests in parallel** (requires separate browser instances)
2. **Use headless mode** for faster execution
3. **Reduce wait timeouts** once tests are stable
4. **Use CSS selectors** instead of XPath (faster)
5. **Minimize file I/O** during tests

## Next Steps

1. ✅ Update selectors based on your HTML structure
2. ✅ Add more test cases for edge cases
3. ✅ Integrate with CI/CD pipeline
4. ✅ Generate HTML reports: `./vendor/bin/phpunit --html tests/report.html tests/Selenium`

## Resources

- [Selenium WebDriver PHP Documentation](https://github.com/php-webdriver/php-webdriver)
- [ChromeDriver Downloads](https://chromedriver.chromium.org/)
- [PHPUnit Documentation](https://phpunit.de/)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)

## Support

For issues or questions:
1. Check test output for specific error messages
2. Review Selenium logs in ChromeDriver terminal
3. Verify selectors match your HTML structure
4. Ensure all prerequisites are installed correctly
