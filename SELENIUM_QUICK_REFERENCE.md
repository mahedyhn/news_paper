# Selenium Tests - Quick Reference Guide

## 🚀 Quick Commands

### Installation
```bash
# Install WebDriver
composer require facebook/webdriver --dev

# Verify installation
./vendor/bin/phpunit --version
```

### Running Tests

```bash
# All Selenium tests
./vendor/bin/phpunit tests/Selenium

# Specific test file
./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php

# Specific test method
./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php --filter test_user_can_login_with_valid_credentials

# Verbose output
./vendor/bin/phpunit tests/Selenium --verbose

# With coverage report
./vendor/bin/phpunit tests/Selenium --coverage-html tests/coverage

# Generate HTML report
./vendor/bin/phpunit tests/Selenium --html tests/report.html

# Stop on first failure
./vendor/bin/phpunit tests/Selenium --stop-on-failure

# Only run tests with specific name pattern
./vendor/bin/phpunit tests/Selenium --filter login
```

### Starting Services

```bash
# Terminal 1: Laravel Server
php artisan serve
php artisan serve --port=8001              # Custom port

# Terminal 2: ChromeDriver
chromedriver                               # macOS/Linux
chromedriver.exe                           # Windows
chromedriver --port=9516                   # Custom port

# Terminal 3: Run Tests (see above)
```

---

## 📝 Common Test Patterns

### Simple Login Test
```php
public function test_user_login(): void
{
    $this->visit('/login');
    
    $this->fillInput('email', 'test@example.com');
    $this->fillInput('password', 'password');
    $this->submitForm('form');
    
    sleep(2);
    $this->seeUrl('/dashboard');
}
```

### Test with Assertions
```php
public function test_form_validation(): void
{
    $this->visit('/login');
    
    $this->fillInput('email', 'invalid-email');
    $this->submitForm('form');
    
    $this->seeUrl('/login');  // Still on login page
    $this->seeText('Invalid');  // Error message shown
}
```

### Test with Waits
```php
public function test_async_action(): void
{
    $this->visit('/login');
    $this->fillInput('email', 'test@example.com');
    $this->submitForm('form');
    
    // Wait for specific element
    $this->waitForElement('.success-message', 10);
    
    // Wait for text to appear
    $this->waitForText('Welcome', 10);
}
```

### Test Social Login
```php
public function test_google_login_button(): void
{
    $this->visit('/login');
    
    // Check button exists
    $this->waitForElement('a[href*="auth/google"]');
    
    // Don't actually click (would redirect to Google)
    $button = $this->driver->findElement(
        WebDriverBy::xpath("//a[contains(@href, 'auth/google')]")
    );
    $this->assertNotNull($button);
}
```

### Test Form Errors
```php
public function test_registration_errors(): void
{
    $this->visit('/register');
    $this->fillInput('name', 'Test');
    $this->fillInput('email', 'invalid-email');
    $this->fillInput('password', 'short');
    $this->fillInput('password_confirmation', 'different');
    
    $this->submitForm('form');
    
    // Multiple assertions
    $this->seeText('email');       // Email error
    $this->seeText('password');    // Password error
    $this->seeUrl('/register');    // Still on page
}
```

---

## 🎯 Useful Selectors

### By HTML Attribute
```php
// By ID
WebDriverBy::id('submit-btn')

// By Class
WebDriverBy::className('btn-primary')

// By Name
WebDriverBy::name('password')

// By CSS Selector
WebDriverBy::cssSelector('.login-form .submit-btn')

// By XPath
WebDriverBy::xpath("//button[@type='submit']")

// By Link Text
WebDriverBy::linkText('Login')

// By Partial Link Text
WebDriverBy::partialLinkText('Log')
```

### Complex Selectors
```php
// Element containing text
WebDriverBy::xpath("//*[contains(text(), 'Login')]")

// Element with multiple classes
WebDriverBy::xpath("//button[@class='btn btn-primary']")

// Parent element
WebDriverBy::xpath("//input[@name='email']/ancestor::form")

// Following sibling
WebDriverBy::xpath("//label[text()='Email']/following-sibling::input")

// Any element with attribute
WebDriverBy::xpath("//*[@data-testid='login-button']")
```

---

## 🔧 Helper Methods Cheat Sheet

```php
// Navigate
$this->visit('/login');                    // Go to URL
$this->seeUrl('/dashboard');               // Assert URL
$currentUrl = $this->driver->getCurrentURL();

// Fill & Click
$this->fillInput('name', 'value');         // Fill input
$this->click('.button-class');             // Click by selector
$this->clickByText('Submit');              // Click by text
$this->submitForm('form');                 // Submit form

// Text Assertions
$this->seeText('Welcome');                 // Text exists
$this->dontSeeText('Error');               // Text missing

// Wait
$this->waitForElement('.popup', 10);       // Wait for element
$this->waitForText('Loaded', 10);          // Wait for text

// Utilities
$this->clearCookies();                     // Clear cookies
$this->getPageSource();                    // Get HTML
$this->driver->getCurrentURL();            // Get URL

// Element Interaction
$element = $this->driver->findElement(WebDriverBy::id('email'));
$element->sendKeys('test@example.com');    // Type text
$element->clear();                         // Clear field
$element->submit();                        // Submit form
$element->getText();                       // Get text
$element->getAttribute('value');           // Get attribute
```

---

## 🧪 Test Data

### From TestDataFactory
```php
use Tests\Selenium\TestDataFactory;

// Get random data
TestDataFactory::randomEmail();            // email@example.com
TestDataFactory::randomName();             // John User123456

// Get test credentials
TestDataFactory::getTestUserCredentials();  // ['email' => '...', 'password' => '...']

// Get passwords
TestDataFactory::getStrongPassword();       // Password123!@#
TestDataFactory::getWeakPassword();         // 123

// Get invalid data
TestDataFactory::getInvalidEmails();        // Array of invalid emails
TestDataFactory::getCommonPasswords();      // Array of common passwords
```

### Create in Test
```php
$email = 'test' . time() . '@example.com';
$password = 'TempPassword123!';
$name = 'Test User ' . time();

// Use in test
$this->fillInput('email', $email);
$this->fillInput('password', $password);
$this->fillInput('name', $name);
```

---

## 🐛 Debug Methods

### Print Information
```php
// Print page URL
echo $this->driver->getCurrentURL();

// Print page title
echo $this->driver->getTitle();

// Print page HTML
echo $this->getPageSource();

// Print element text
$element = $this->driver->findElement(WebDriverBy::id('message'));
echo $element->getText();

// Print all cookies
foreach ($this->driver->manage()->getCookies() as $cookie) {
    echo $cookie['name'] . ': ' . $cookie['value'];
}
```

### Take Screenshot
```php
// Before taking screenshot, ensure directory exists
if (!is_dir('tests/Selenium/screenshots')) {
    mkdir('tests/Selenium/screenshots', 0777, true);
}

// Take screenshot
$this->driver->takeScreenshot('tests/Selenium/screenshots/test-name.png');
```

### Wait and Retry
```php
// Wait with custom condition
$wait = new WebDriverWait($this->driver, 10);
$wait->until(function ($driver) {
    return $driver->findElement(WebDriverBy::id('success'))->isDisplayed();
});

// Manual retry
$maxRetries = 3;
for ($i = 0; $i < $maxRetries; $i++) {
    try {
        $element = $this->driver->findElement(WebDriverBy::id('element'));
        break;
    } catch (\Exception $e) {
        if ($i === $maxRetries - 1) throw $e;
        sleep(1);
    }
}
```

---

## ⚙️ Configuration Changes

### Enable Headless Mode
Edit `SeleniumTestCase.php`:
```php
$options->addArguments(['headless', 'disable-gpu']);
```

### Change Window Size
```php
$options->addArguments(['window-size=1280,720']);
```

### Change ChromeDriver Port
In test:
```php
RemoteWebDriver::create(
    'http://localhost:9516',  // Custom port
    $capabilities
);
```

### Change Base URL
In `.env`:
```env
APP_URL=http://localhost:8001
```

---

## 📊 Test Organization

### Run by Test Class
```bash
./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php
```

### Run by Test Method
```bash
./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php --filter test_user_can_login_with_valid_credentials
```

### Run by Pattern
```bash
./vendor/bin/phpunit tests/Selenium --filter login       # All login tests
./vendor/bin/phpunit tests/Selenium --filter register    # All register tests
./vendor/bin/phpunit tests/Selenium --filter security    # All security tests
```

### Run with Specific Config
```bash
./vendor/bin/phpunit tests/Selenium -c phpunit-selenium.xml
```

---

## 🚨 Common Errors & Solutions

| Error | Solution |
|-------|----------|
| `Could not connect to localhost:9515` | Start ChromeDriver in new terminal |
| `Element not found` | Update selector, add wait, check element exists |
| `Test timeout` | Increase timeout, check network, verify app running |
| `Session not created` | Check Chrome version matches ChromeDriver |
| `Permission denied` | Make scripts executable: `chmod +x quick-start.sh` |
| `XAMPP not running` | Start Apache/MySQL services |
| `Database not found` | Run migrations: `php artisan migrate` |

---

## 🔄 Workflow Example

```bash
# 1. Setup (one-time)
composer require facebook/webdriver --dev
# Download ChromeDriver to project root

# 2. Create test user
php artisan tinker
# Create user...

# 3. Start services in different terminals
# Terminal 1
php artisan serve

# Terminal 2
chromedriver

# Terminal 3
# Run tests
./vendor/bin/phpunit tests/Selenium --filter test_user_can_login_with_valid_credentials

# 4. View results
./vendor/bin/phpunit tests/Selenium --html tests/report.html
# Open tests/report.html in browser
```

---

## 📚 Additional Resources

- [Selenium PHP WebDriver Docs](https://github.com/php-webdriver/php-webdriver)
- [ChromeDriver Documentation](https://chromedriver.chromium.org/)
- [PHPUnit Assertions](https://phpunit.de/documentation.html)
- [WebDriver Locators](https://www.selenium.dev/documentation/webdriver/locator_strategies/)

---

**Last Updated:** 2026-02-08  
**Version:** 1.0.0
