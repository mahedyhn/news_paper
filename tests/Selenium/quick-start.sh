#!/bin/bash
# Quick Start Script for Selenium Tests on macOS/Linux
# Run from the project root directory

echo "========================================"
echo "Selenium Authentication Tests - Quick Start"
echo "========================================\n"

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "Installing Composer packages..."
    composer install
fi

# Check if .env file exists
if [ ! -f ".env" ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
    echo "Please update .env with your settings\n"
fi

# Check for ChromeDriver
if ! command -v chromedriver &> /dev/null; then
    echo "[!] ChromeDriver not found!"
    echo "    Please download ChromeDriver from: https://chromedriver.chromium.org/"
    echo "    And place it in your project root or system PATH\n"
fi

echo "Starting Laravel development server..."
echo "Note: Keep this terminal open while running tests\n"

# Start Laravel server in background
php artisan serve &
SERVER_PID=$!

sleep 2

echo "Server started at http://localhost:8000"
echo "In a new terminal, run: chromedriver"
echo ""
echo "Then in another terminal, run one of these commands:"
echo ""
echo "  # Run all Selenium tests"
echo "  ./vendor/bin/phpunit tests/Selenium"
echo ""
echo "  # Run specific test file"
echo "  ./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php"
echo ""
echo "  # Run specific test method"
echo "  ./vendor/bin/phpunit tests/Selenium/AuthenticationSeleniumTest.php --filter test_user_can_see_login_form"
echo ""
echo "  # Run tests with verbose output"
echo "  ./vendor/bin/phpunit tests/Selenium --verbose"
echo ""

echo "Press Ctrl+C to stop the server"

wait $SERVER_PID
