<?php

namespace Tests\Selenium;

use Exception;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * Test Data Factory for Selenium Tests
 * 
 * Provides utilities for creating test data
 */
class TestDataFactory
{
    /**
     * Generate random email
     */
    public static function randomEmail(): string
    {
        return 'test' . time() . rand(1000, 9999) . '@example.com';
    }

    /**
     * Generate random name
     */
    public static function randomName(): string
    {
        $names = ['John', 'Jane', 'Bob', 'Alice', 'Charlie', 'Diana', 'Eve', 'Frank'];
        return $names[array_rand($names)] . ' ' . 'User' . time();
    }

    /**
     * Get test credentials from environment
     */
    public static function getTestUserCredentials(): array
    {
        return [
            'email' => env('TEST_USER_EMAIL', 'test@example.com'),
            'password' => env('TEST_USER_PASSWORD', 'password'),
            'name' => env('TEST_USER_NAME', 'Test User'),
        ];
    }

    /**
     * Get weak password that fails validation
     */
    public static function getWeakPassword(): string
    {
        return '123'; // Too short, no uppercase, no special chars
    }

    /**
     * Get strong password that passes validation
     */
    public static function getStrongPassword(): string
    {
        return 'TestPassword123!@#';
    }

    /**
     * Get invalid email formats
     */
    public static function getInvalidEmails(): array
    {
        return [
            'notanemail',
            'missing@domain',
            '@nodomain.com',
            'spaces in@email.com',
            'double..dot@email.com',
        ];
    }

    /**
     * Get common test passwords
     */
    public static function getCommonPasswords(): array
    {
        return [
            'password',
            'password123',
            'test123',
            'admin123',
            'qwerty123',
        ];
    }
}
