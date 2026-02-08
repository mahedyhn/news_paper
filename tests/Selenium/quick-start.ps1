#!/bin/powershell
# Quick Start Script for Selenium Tests on Windows
# Run from the project root directory

Write-Host "========================================" -ForegroundColor Green
Write-Host "Selenium Authentication Tests - Quick Start" -ForegroundColor Green
Write-Host "========================================`n" -ForegroundColor Green

# Check if vendor directory exists
if (-not (Test-Path "vendor")) {
    Write-Host "Installing Composer packages..." -ForegroundColor Yellow
    composer install
}

# Check if ChromeDriver is installed
if (-not (Test-Path "chromedriver.exe") -and -not (Get-Command chromedriver -ErrorAction SilentlyContinue)) {
    Write-Host "`n[!] ChromeDriver not found!" -ForegroundColor Red
    Write-Host "    Please download ChromeDriver from: https://chromedriver.chromium.org/" -ForegroundColor Yellow
    Write-Host "    And place it in your project root or system PATH`n" -ForegroundColor Yellow
}

# Check if .env file exists
if (-not (Test-Path ".env")) {
    Write-Host "Creating .env file from .env.example..." -ForegroundColor Yellow
    Copy-Item ".env.example" ".env" -Force
    Write-Host "Please update .env with your settings`n" -ForegroundColor Yellow
}

Write-Host "Starting Laravel development server..." -ForegroundColor Cyan
Write-Host "Note: Keep this terminal open while running tests`n" -ForegroundColor Gray

# Start Laravel server in background
Start-Process powershell -ArgumentList "php artisan serve"

Start-Sleep -Seconds 2

Write-Host "Server started at http://localhost:8000" -ForegroundColor Green
Write-Host "In a new terminal, run: chromedriver" -ForegroundColor Yellow
Write-Host "`nThen in another terminal, run one of these commands:" -ForegroundColor Cyan
Write-Host "`n  # Run all Selenium tests" -ForegroundColor Gray
Write-Host "  .\vendor\bin\phpunit tests/Selenium" -ForegroundColor White
Write-Host "`n  # Run specific test file" -ForegroundColor Gray
Write-Host "  .\vendor\bin\phpunit tests/Selenium/AuthenticationSeleniumTest.php" -ForegroundColor White
Write-Host "`n  # Run specific test method" -ForegroundColor Gray
Write-Host "  .\vendor\bin\phpunit tests/Selenium/AuthenticationSeleniumTest.php --filter test_user_can_see_login_form" -ForegroundColor White
Write-Host "`n  # Run tests with verbose output" -ForegroundColor Gray
Write-Host "  .\vendor\bin\phpunit tests/Selenium --verbose`n" -ForegroundColor White

Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
