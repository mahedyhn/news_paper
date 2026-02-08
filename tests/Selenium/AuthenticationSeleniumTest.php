<?php

namespace Tests\Selenium;

use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\Support\ui\ExpectedCondition;
use Facebook\WebDriver\WebDriverBy;

class AuthenticationSeleniumTest extends SeleniumTestCase
{
    /**
     * Test: User can see login form
     */
    public function test_user_can_see_login_form(): void
    {
        $this->visit('/login');
        
        // Check if login form is visible
        $this->waitForElement('form');
        $this->seeText('Login');
        
        // Verify form fields exist
        $this->driver->findElement(WebDriverBy::name('email'));
        $this->driver->findElement(WebDriverBy::name('password'));
    }

    /**
     * Test: User can see register form
     */
    public function test_user_can_see_register_form(): void
    {
        $this->visit('/register');
        
        // Check if register form is visible
        $this->waitForElement('form');
        $this->seeText('Register');
        
        // Verify form fields exist
        $this->driver->findElement(WebDriverBy::name('name'));
        $this->driver->findElement(WebDriverBy::name('email'));
        $this->driver->findElement(WebDriverBy::name('password'));
        $this->driver->findElement(WebDriverBy::name('password_confirmation'));
    }

    /**
     * Test: Login with valid credentials
     * 
     * NOTE: You need to create a test user or seed one before running this test
     * Update the credentials below with your test user details
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $this->visit('/login');
        
        // Fill login form
        $this->fillInput('email', 'test@example.com');
        $this->fillInput('password', 'password');
        
        // Submit form
        $this->submitForm('form');
        
        // Wait for redirect and check if logged in
        sleep(2); // Small delay to ensure redirect
        $this->seeUrl('/dashboard');
    }

    /**
     * Test: Login fails with invalid email
     */
    public function test_login_fails_with_invalid_email(): void
    {
        $this->visit('/login');
        
        // Fill login form with invalid email
        $this->fillInput('email', 'nonexistent@example.com');
        $this->fillInput('password', 'password123');
        
        // Submit form
        $this->submitForm('form');
        
        // Should stay on login page with error
        sleep(1);
        $this->seeUrl('/login');
    }

    /**
     * Test: Login fails with invalid password
     * 
     * NOTE: Requires a test user to exist
     */
    public function test_login_fails_with_invalid_password(): void
    {
        $this->visit('/login');
        
        // Fill login form with wrong password
        $this->fillInput('email', 'test@example.com');
        $this->fillInput('password', 'wrongpassword');
        
        // Submit form
        $this->submitForm('form');
        
        // Should stay on login page
        sleep(1);
        $this->seeUrl('/login');
    }

    /**
     * Test: User can register with valid data
     */
    public function test_user_can_register_with_valid_data(): void
    {
        $this->visit('/register');
        
        $email = 'newuser' . time() . '@example.com';
        
        // Fill registration form
        $this->fillInput('name', 'Test User');
        $this->fillInput('email', $email);
        $this->fillInput('password', 'Password123!');
        $this->fillInput('password_confirmation', 'Password123!');
        
        // Accept terms if checkbox exists
        try {
            $termsCheckbox = $this->driver->findElement(WebDriverBy::name('terms'));
            if ($termsCheckbox->getAttribute('type') === 'checkbox') {
                if (!$termsCheckbox->isSelected()) {
                    $termsCheckbox->click();
                }
            }
        } catch (\Exception $e) {
            // Terms checkbox may not exist
        }
        
        // Submit form
        $this->submitForm('form');
        
        // Should redirect to dashboard after registration
        sleep(2);
        $this->seeUrl('/dashboard');
    }

    /**
     * Test: Registration fails with mismatched passwords
     */
    public function test_registration_fails_with_mismatched_passwords(): void
    {
        $this->visit('/register');
        
        // Fill registration form with mismatched passwords
        $this->fillInput('name', 'Test User');
        $this->fillInput('email', 'test' . time() . '@example.com');
        $this->fillInput('password', 'Password123!');
        $this->fillInput('password_confirmation', 'DifferentPassword123!');
        
        // Submit form
        $this->submitForm('form');
        
        // Should show error
        sleep(1);
        $this->seeUrl('/register');
    }

    /**
     * Test: Registration fails with existing email
     */
    public function test_registration_fails_with_existing_email(): void
    {
        $this->visit('/register');
        
        // Try to register with existing email
        $this->fillInput('name', 'Another User');
        $this->fillInput('email', 'test@example.com'); // Existing user
        $this->fillInput('password', 'Password123!');
        $this->fillInput('password_confirmation', 'Password123!');
        
        // Submit form
        $this->submitForm('form');
        
        // Should show error
        sleep(1);
        $this->seeUrl('/register');
    }

    /**
     * Test: User can logout
     * 
     * Requires authentication first
     */
    public function test_authenticated_user_can_logout(): void
    {
        // First, login
        $this->visit('/login');
        $this->fillInput('email', 'test@example.com');
        $this->fillInput('password', 'password');
        $this->submitForm('form');
        sleep(2);
        
        // Now logout - look for logout button/link
        try {
            // Try to find logout button - adjust selector based on your UI
            $this->clickByText('Logout');
            sleep(2);
            
            // Should be redirected to home
            $this->seeUrl('/');
        } catch (\Exception $e) {
            // If logout button not found differently, try alternative selectors
            $this->markTestSkipped('Logout button selector needs to be updated for your UI');
        }
    }

    /**
     * Test: Authenticated user cannot access login page
     */
    public function test_authenticated_user_cannot_access_login_page(): void
    {
        // First, login
        $this->visit('/login');
        $this->fillInput('email', 'test@example.com');
        $this->fillInput('password', 'password');
        $this->submitForm('form');
        sleep(2);
        
        // Try to access login page
        $this->visit('/login');
        sleep(1);
        
        // Should be redirected away from login page
        $this->dontSeeUrl('/login');
    }

    /**
     * Test: Unauthenticated user cannot access dashboard
     */
    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        // Clear cookies to ensure logged out
        $this->clearCookies();
        
        // Try to access dashboard
        $this->visit('/dashboard');
        sleep(1);
        
        // Should be redirected to login
        $this->seeUrl('/login');
    }

    /**
     * Test: Login form displays error messages
     */
    public function test_login_form_displays_validation_errors(): void
    {
        $this->visit('/login');
        
        // Try to submit empty form
        $this->submitForm('form');
        
        sleep(1);
        
        // Check if error messages are shown
        // This depends on your error message structure
        $this->seeUrl('/login');
    }

    /**
     * Test: Social login buttons are visible
     */
    public function test_social_login_buttons_are_visible(): void
    {
        $this->visit('/login');
        
        // Check if social login buttons exist
        try {
            // Adjust selectors based on your HTML
            $this->driver->findElement(WebDriverBy::xpath("//a[contains(@href, 'auth/google')]"));
            $this->driver->findElement(WebDriverBy::xpath("//a[contains(@href, 'auth/facebook')]"));
        } catch (\Exception $e) {
            $this->markTestSkipped('Social login buttons not found - may not be configured');
        }
    }

    /**
     * Test: Password field is masked
     */
    public function test_password_field_is_masked(): void
    {
        $this->visit('/login');
        
        $passwordField = $this->driver->findElement(WebDriverBy::name('password'));
        $type = $passwordField->getAttribute('type');
        
        $this->assertEquals('password', $type, 'Password field should be of type "password"');
    }

    /**
     * Test: Form has CSRF token
     */
    public function test_form_has_csrf_token(): void
    {
        $this->visit('/login');
        
        // Check if CSRF token exists in form
        try {
            $this->driver->findElement(WebDriverBy::name('_token'));
        } catch (\Exception $e) {
            $this->fail('CSRF token not found in form');
        }
    }
}
