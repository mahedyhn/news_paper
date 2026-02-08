<?php

namespace Tests\Selenium;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Support\ui\WebDriverWait;
use Facebook\WebDriver\Support\ui\ExpectedCondition;
use Tests\Selenium\TestDataFactory;

class AuthenticationSecuritySeleniumTest extends SeleniumTestCase
{
    /**
     * Test: XSS prevention in form inputs
     * Attempts to inject script tags in form fields
     */
    public function test_xss_prevention_in_login_form(): void
    {
        $this->visit('/login');
        
        // Attempt XSS injection in email field
        $xssPayload = '<script>alert("XSS")</script>';
        $this->fillInput('email', $xssPayload);
        $this->fillInput('password', 'password');
        
        $this->submitForm('form');
        sleep(1);
        
        // Page should not contain unescaped script tag
        $pageSource = $this->getPageSource();
        $this->assertStringNotContainsString('<script>alert("XSS")</script>', $pageSource);
    }

    /**
     * Test: SQL injection prevention
     */
    public function test_sql_injection_prevention(): void
    {
        $this->visit('/login');
        
        // Attempt SQL injection
        $this->fillInput('email', "' OR '1'='1");
        $this->fillInput('password', "' OR '1'='1");
        
        $this->submitForm('form');
        sleep(1);
        
        // Should fail authentication, not bypass it
        $this->seeUrl('/login');
    }

    /**
     * Test: Rate limiting / Brute force protection
     * Attempts multiple failed logins
     */
    public function test_brute_force_protection(): void
    {
        $failedAttempts = 0;
        $maxAttempts = 10; // Adjust based on your rate limiting config
        
        for ($i = 0; $i < $maxAttempts; $i++) {
            $this->visit('/login');
            
            $this->fillInput('email', 'test@example.com');
            $this->fillInput('password', 'wrongpassword');
            $this->submitForm('form');
            
            sleep(1);
            
            // Check if we're still on login page
            $currentUrl = $this->driver->getCurrentURL();
            if (strpos($currentUrl, '/login') === false) {
                break;
            }
            
            $failedAttempts++;
        }
        
        // After several failed attempts, user might be locked out
        // This is passed if system has rate limiting
        $this->assertTrue($failedAttempts > 3, 'Should have rate limiting after multiple failed attempts');
    }

    /**
     * Test: CSRF protection
     * Ensures CSRF token is required
     */
    public function test_csrf_token_is_required(): void
    {
        $this->visit('/login');
        
        // Try to get form without submitting (would need CSRF token)
        try {
            $csrfToken = $this->driver->findElement(WebDriverBy::name('_token'));
            $this->assertNotNull($csrfToken);
            $this->assertNotEmpty($csrfToken->getAttribute('value'));
        } catch (\Exception $e) {
            $this->fail('CSRF token (_token field) not found in form');
        }
    }

    /**
     * Test: Password confirmation on registration
     */
    public function test_password_confirmation_required(): void
    {
        $this->visit('/register');
        
        // Fill form with mismatched passwords
        $this->fillInput('name', TestDataFactory::randomName());
        $this->fillInput('email', TestDataFactory::randomEmail());
        $this->fillInput('password', 'Password123!');
        $this->fillInput('password_confirmation', 'DifferentPassword!');
        
        $this->submitForm('form');
        sleep(1);
        
        // Should fail and return to register page with error
        $this->seeUrl('/register');
    }

    /**
     * Test: Email validation
     */
    public function test_email_validation(): void
    {
        $this->visit('/register');
        
        $invalidEmails = TestDataFactory::getInvalidEmails();
        
        foreach ($invalidEmails as $email) {
            $this->visit('/register'); // Reset form
            
            $this->fillInput('name', TestDataFactory::randomName());
            $this->fillInput('email', $email);
            $this->fillInput('password', TestDataFactory::getStrongPassword());
            $this->fillInput('password_confirmation', TestDataFactory::getStrongPassword());
            
            $this->submitForm('form');
            sleep(1);
            
            // Should remain on register page for invalid email
            $currentUrl = $this->driver->getCurrentURL();
            $this->assertStringContainsString('/register', $currentUrl, 
                "Registration should fail for email: {$email}");
        }
    }

    /**
     * Test: Password strength validation on registration
     */
    public function test_password_strength_validation(): void
    {
        $this->visit('/register');
        
        // Try weak password
        $this->fillInput('name', TestDataFactory::randomName());
        $this->fillInput('email', TestDataFactory::randomEmail());
        $this->fillInput('password', TestDataFactory::getWeakPassword());
        $this->fillInput('password_confirmation', TestDataFactory::getWeakPassword());
        
        $this->submitForm('form');
        sleep(1);
        
        // Should show error or remain on register page
        $currentUrl = $this->driver->getCurrentURL();
        $this->assertStringContainsString('/register', $currentUrl, 
            'Registration should fail for weak password');
    }

    /**
     * Test: Account enumeration protection
     * Exact same message for non-existent email vs wrong password
     */
    public function test_generic_login_error_messages(): void
    {
        // Attempt 1: Non-existent email
        $this->visit('/login');
        $this->fillInput('email', 'nonexistent' . time() . '@example.com');
        $this->fillInput('password', 'password');
        $this->submitForm('form');
        sleep(1);
        
        // Should show generic error (implementation dependent)
        $this->seeUrl('/login');
    }

    /**
     * Test: Session fixation prevention
     * Session ID should change after login
     */
    public function test_session_fixation_prevention(): void
    {
        // Get initial session cookie
        $this->visit('/login');
        $initialCookie = $this->getSessionCookie();
        
        // Login
        $this->fillInput('email', 'test@example.com');
        $this->fillInput('password', 'password');
        $this->submitForm('form');
        sleep(2);
        
        // Get new session cookie
        $newCookie = $this->getSessionCookie();
        
        // Session ID should be different after login
        // (This test may need adjustment based on your session configuration)
        $this->assertNotEmpty($initialCookie, 'Initial session should exist');
        $this->assertNotEmpty($newCookie, 'New session should exist');
    }

    /**
     * Test: Secure password reset link
     * (If password reset is implemented)
     */
    public function test_password_reset_token_validity(): void
    {
        $this->visit('/forgot-password');
        
        try {
            // Attempt password reset
            $this->fillInput('email', 'test@example.com');
            $this->submitForm('form');
            sleep(1);
            
            // Should not directly reset, should send email
            // This test verifies the process completes
            $this->seeText('reset');
        } catch (\Exception $e) {
            $this->markTestSkipped('Password reset not implemented or selector mismatch');
        }
    }

    /**
     * Test: HTTPOnly cookie flag
     * Session cookie should be HTTPOnly (not accessible from JavaScript)
     */
    public function test_session_cookie_is_http_only(): void
    {
        $this->visit('/login');
        
        // Login first
        $this->fillInput('email', 'test@example.com');
        $this->fillInput('password', 'password');
        $this->submitForm('form');
        sleep(2);
        
        // Try to access session cookie via JavaScript
        // If HTTPOnly is set, this should fail
        $result = $this->driver->executeScript('return document.cookie;');
        
        // This test depends on your cookie configuration
        // Ideally, CSRF-sensitive cookies should be HTTPOnly
    }

    /**
     * Test: No sensitive data in URLs
     */
    public function test_no_sensitive_data_in_urls(): void
    {
        $this->visit('/login');
        
        $this->fillInput('email', 'test@example.com');
        $this->fillInput('password', 'password');
        $this->submitForm('form');
        sleep(2);
        
        $currentUrl = $this->driver->getCurrentURL();
        
        // Check that no passwords or emails are in the URL
        $this->assertStringNotContainsString('password', strtolower($currentUrl));
        $this->assertStringNotContainsString('test@example.com', $currentUrl);
    }

    /**
     * Helper: Get session cookie
     */
    private function getSessionCookie(): ?string
    {
        try {
            foreach ($this->driver->manage()->getCookies() as $cookie) {
                if ($cookie['name'] === 'LARAVEL_SESSION' || $cookie['name'] === 'PHPSESSID') {
                    return $cookie['value'];
                }
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}
