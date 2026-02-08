<?php

/**
 * Selenium Configuration for Authentication Tests
 * 
 * This file contains configuration for running Selenium tests
 */

return [
    // Base URL for the application
    'base_url' => env('APP_URL', 'http://localhost'),

    // ChromeDriver settings
    'chromedriver' => [
        'host' => 'localhost',
        'port' => 9515,
    ],

    // Browser window size
    'window' => [
        'width' => 1920,
        'height' => 1080,
    ],

    // Wait timeout in seconds
    'wait_timeout' => 10,

    // Test user credentials for authentication tests
    'test_user' => [
        'email' => env('TEST_USER_EMAIL', 'test@example.com'),
        'password' => env('TEST_USER_PASSWORD', 'password'),
        'name' => 'Test User',
    ],

    // Enable/disable headless mode
    'headless' => env('SELENIUM_HEADLESS', false),

    // Additional Chrome options
    'chrome_options' => [
        'disable-blink-features=AutomationControlled',
        'user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    ],
];
