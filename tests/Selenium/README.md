# Selenium Authentication Tests

Complete end-to-end browser automation tests for your Laravel authentication system.

## 📋 What's Included

### Test Files

1. **AuthenticationSeleniumTest.php** - Main authentication tests
   - Login/Registration form visibility
   - Valid/Invalid login scenarios
   - User registration flow
   - Session management
   - Authorization checks
   - Social login button verification
   - Form security (CSRF tokens, password masking)

2. **AuthenticationSecuritySeleniumTest.php** - Security-focused tests
   - XSS Prevention (Cross-Site Scripting)
   - SQL Injection Prevention
   - Brute Force Protection
   - CSRF Token Verification
   - Email Validation
   - Password Strength Validation
   - Account Enumeration Protection
   - Session Fixation Prevention
   - Cookie Security (HTTPOnly flags)

3. **SeleniumTestCase.php** - Base test class
   - WebDriver initialization
   - Common helper methods
   - Element interaction utilities
   - Wait/Synchronization methods

4. **TestDataFactory.php** - Test data utilities
   - Random test data generation
   - Test credentials management
   - Invalid data for negative tests

## 🚀 Quick Start

### 1. Prerequisites Check

```bash
# Check PHP version (7.4+)
php --version

# Check if Composer is installed
composer --version

# Check Chrome version (Menu → Help → About Google Chrome)
```

### 2. Install Dependencies

```bash
composer require facebook/webdriver --dev
```

### 3. Download ChromeDriver

1. Get your Chrome version from Settings → Help → About Google Chrome
2. Download matching ChromeDriver from: https://chromedriver.chromium.org/
3. Extract to project root or add to PATH

### 4. Create Test User

Open terminal in project root:

```bash
php artisan tinker
```

Then create a test user:

```php
App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);
exit;
```

### 5. Start Services

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - ChromeDriver:**
```bash
# Windows
chromedriver.exe

# macOS/Linux
./chromedriver
```

**Terminal 3 - Run Tests:**
```bash
# All tests
./vendor/bin/phpunit tests/Selenium

# Specific test file
./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php

# Specific test
./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php --filter test_user_can_login_with_valid_credentials

# With output
./vendor/bin/phpunit tests/Selenium --verbose
```

## 📝 Test Cases

### Basic Authentication

- ✅ `test_user_can_see_login_form`
- ✅ `test_user_can_see_register_form`
- ✅ `test_user_can_login_with_valid_credentials`
- ✅ `test_login_fails_with_invalid_email`
- ✅ `test_login_fails_with_invalid_password`
- ✅ `test_user_can_register_with_valid_data`
- ✅ `test_registration_fails_with_mismatched_passwords`
- ✅ `test_registration_fails_with_existing_email`

### Session & Authorization

- ✅ `test_authenticated_user_can_logout`
- ✅ `test_authenticated_user_cannot_access_login_page`
- ✅ `test_unauthenticated_user_cannot_access_dashboard`

### Form Security

- ✅ `test_password_field_is_masked`
- ✅ `test_form_has_csrf_token`
- ✅ `test_login_form_displays_validation_errors`

### Social Login

- ✅ `test_social_login_buttons_are_visible`

### Security Tests

- ✅ `test_xss_prevention_in_login_form`
- ✅ `test_sql_injection_prevention`
- ✅ `test_brute_force_protection`
- ✅ `test_csrf_token_is_required`
- ✅ `test_password_confirmation_required`
- ✅ `test_email_validation`
- ✅ `test_password_strength_validation`
- ✅ `test_generic_login_error_messages`
- ✅ `test_session_fixation_prevention`
- ✅ `test_no_sensitive_data_in_urls`

## 🛠️ Customization

### Update Test Selectors

If your HTML structure differs, update selectors in the test files:

**Example: Change login button selector**

```php
// Before
$this->submitForm('form');

// After - if your form has a specific ID
$this->submitForm('#login-form');

// Or find by name
$element = $this->driver->findElement(WebDriverBy::name('submit_btn'));
$element->click();
```

### Add Custom Test Methods

```php
public function test_custom_scenario(): void
{
    $this->visit('/login');
    
    // Your test code
    $this->fillInput('email', 'test@example.com');
    $this->fillInput('password', 'password');
    
    // Add assertion
    $this->seeText('Welcome');
}
```

### Enable Headless Mode

Edit `SeleniumTestCase.php`:

```php
$options->addArguments(['headless', 'disable-gpu']);
```

Or set environment variable:

```env
SELENIUM_HEADLESS=true
```

## ⚙️ Configuration

### Environment Variables (.env)

```env
APP_URL=http://localhost:8000
TEST_USER_EMAIL=test@example.com
TEST_USER_PASSWORD=password
TEST_USER_NAME=Test User
```

### selenium.config.php

Modify default settings:

```php
'base_url' => env('APP_URL', 'http://localhost'),
'wait_timeout' => 10,  // seconds
'headless' => env('SELENIUM_HEADLESS', false),
```

## 🔍 Debugging

### Take Screenshots on Failure

Add to `SeleniumTestCase.php`:

```php
protected function onNotSuccessfulTest(\Throwable $t): void
{
    mkdir('tests/Selenium/screenshots', 0777, true);
    $filename = 'tests/Selenium/screenshots/' . time() . '.png';
    $this->driver->takeScreenshot($filename);
    parent::onNotSuccessfulTest($t);
}
```

### View Browser Activity

Comment out or remove headless mode to see the browser in action:

```php
// $options->addArguments(['headless']);
```

### Print Page Content

```php
echo "\n=== Page Content ===\n";
echo $this->getPageSource();
echo "\n=== End ===\n";
```

### Print Current URL

```php
echo "Current URL: " . $this->driver->getCurrentURL() . "\n";
```

## 📊 Test Reports

### HTML Report

```bash
./vendor/bin/phpunit tests/Selenium --html tests/report.html
```

Opens: `tests/report.html` in browser

### Coverage Report

```bash
./vendor/bin/phpunit tests/Selenium --coverage-html tests/coverage
```

### JUnit XML (for CI/CD)

```bash
./vendor/bin/phpunit tests/Selenium --log-junit tests/junit.xml
```

## 🐛 Troubleshooting

### "Could not connect to localhost:9515"

```bash
# Verify ChromeDriver is running
# Windows
Get-Process chromedriver

# macOS/Linux
lsof -i :9515

# Check firewall isn't blocking port 9515
```

### Test Timeouts

Increase wait timeout in `SeleniumTestCase.php`:

```php
$this->waitForElement('selector', 20); // 20 seconds
```

### Element Not Found

Verify selectors match your HTML:

```php
// Inspect in browser (F12) to find correct selector
$element = $this->driver->findElement(WebDriverBy::cssSelector('.your-selector'));
```

### Application Not Accessible

```bash
# Check Laravel is running
php artisan serve

# Verify URL works in browser
# Visit http://localhost:8000 manually
```

### ChromeDriver Version Mismatch

```bash
# Get Chrome version
chrome --version   # or check Settings → Help → About

# Download matching ChromeDriver from:
# https://chromedriver.chromium.org/downloads
```

## 🔐 Security Notes

1. **Test User Password**: Use a strong, unique password in production
2. **Test Database**: Use separate test environment
3. **Sensitive Data**: Never commit real credentials to repository
4. **Headless Screenshots**: Disable in production if storing screenshots

## 📚 Resources

- [Selenium WebDriver (PHP)](https://github.com/php-webdriver/php-webdriver)
- [ChromeDriver Documentation](https://chromedriver.chromium.org/)
- [PHPUnit Documentation](https://phpunit.de/)
- [Laravel Authentication](https://laravel.com/docs/authentication)

## ✨ Advanced Features

### Parallel Test Execution

```bash
# Run tests in parallel (requires custom setup)
# Tests must use separate browser instances to avoid conflicts
```

### CI/CD Integration

See `SELENIUM_SETUP.md` for GitHub Actions example

### Screenshot on Failure

Uncomment screenshot method in base class to capture failures

### Performance Testing

Add timing assertions:

```php
$startTime = microtime(true);
$this->visit('/login');
$duration = microtime(true) - $startTime;
$this->assertLessThan(3, $duration, 'Login page should load in < 3s');
```

## 💡 Best Practices

1. **Use explicit waits** instead of sleep()
2. **Keep tests independent** - don't rely on execution order
3. **Use descriptive test names** - they document functionality
4. **Test happy path and error cases** - both matter
5. **Keep selectors stable** - avoid brittle XPath expressions
6. **Use data factories** - keep test data organized
7. **Clean up after tests** - logout, clear cookies

## 🎯 Next Steps

1. Run tests and verify they pass
2. Customize selectors for your HTML
3. Add more test scenarios
4. Integrate with CI/CD
5. Set up screenshot captures on failure
6. Generate HTML reports

## 📞 Support

If tests fail:

1. Check `SeleniumTestCase.php` logs
2. Verify ChromeDriver is running
3. Check application is accessible
4. Review selector accuracy
5. Ensure test user exists in database

---

Happy testing! 🚀
