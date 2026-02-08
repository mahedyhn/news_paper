<?php

namespace Tests\Selenium;

use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\WebDriver;

class SeleniumTestCase extends TestCase
{
    protected WebDriver $driver;
    protected string $baseUrl;

    /**
     * Set up the Selenium WebDriver
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->baseUrl = getenv('APP_URL') ?: 'http://localhost';

        // Initialize Chrome WebDriver
        $this->driver = $this->initializeChromeDriver();
    }

    /**
     * Initialize Chrome WebDriver
     */
    private function initializeChromeDriver(): WebDriver
    {
        $options = new ChromeOptions();
        
        // Uncomment for headless mode (no UI visible)
        // $options->addArguments(['headless', 'disable-gpu']);
        
        // Additional options for stability
        $options->addArguments([
            'disable-blink-features=AutomationControlled',
            'user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'window-size=1920,1080',
        ]);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        // Connect to ChromeDriver (make sure it's running on port 9515)
        return RemoteWebDriver::create(
            'http://localhost:9515',
            $capabilities
        );
    }

    /**
     * Tear down the WebDriver
     */
    protected function tearDown(): void
    {
        if (isset($this->driver)) {
            $this->driver->quit();
        }
        parent::tearDown();
    }

    /**
     * Navigate to a URL
     */
    protected function visit(string $path): void
    {
        $this->driver->get($this->baseUrl . $path);
    }

    /**
     * Get current URL
     */
    protected function seeUrl(string $expectedUrl): void
    {
        $currentUrl = $this->driver->getCurrentURL();
        $this->assertStringContainsString($expectedUrl, $currentUrl);
    }

    /**
     * See text on page
     */
    protected function seeText(string $text): void
    {
        $pageText = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::tagName('body'))->getText();
        $this->assertStringContainsString($text, $pageText);
    }

    /**
     * Don't see text on page
     */
    protected function dontSeeText(string $text): void
    {
        $pageText = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::tagName('body'))->getText();
        $this->assertStringNotContainsString($text, $pageText);
    }

    /**
     * Fill an input field
     */
    protected function fillInput(string $name, string $value): void
    {
        $element = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::name($name));
        $element->clear();
        $element->sendKeys($value);
    }

    /**
     * Click a button or link
     */
    protected function click(string $selector): void
    {
        $element = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector($selector));
        $element->click();
    }

    /**
     * Click by text
     */
    protected function clickByText(string $text): void
    {
        $element = $this->driver->findElement(
            \Facebook\WebDriver\WebDriverBy::xpath("//*[contains(text(), '{$text}')]")
        );
        $element->click();
    }

    /**
     * Submit a form
     */
    protected function submitForm(string $selector = 'form'): void
    {
        $form = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector($selector));
        $form->submit();
    }

    /**
     * Wait for element to be visible
     */
    protected function waitForElement(string $selector, int $timeout = 10): void
    {
        $wait = new \Facebook\WebDriver\Support\ui\WebDriverWait($this->driver, $timeout);
        $wait->until(
            \Facebook\WebDriver\Support\ui\ExpectedCondition::visibilityOfElementLocated(
                \Facebook\WebDriver\WebDriverBy::cssSelector($selector)
            )
        );
    }

    /**
     * Wait for text to appear
     */
    protected function waitForText(string $text, int $timeout = 10): void
    {
        $wait = new \Facebook\WebDriver\Support\ui\WebDriverWait($this->driver, $timeout);
        $wait->until(function () use ($text) {
            return strpos($this->driver->getPageSource(), $text) !== false;
        });
    }

    /**
     * Clear cookies
     */
    protected function clearCookies(): void
    {
        $this->driver->manage()->deleteAllCookies();
    }

    /**
     * Get page source
     */
    protected function getPageSource(): string
    {
        return $this->driver->getPageSource();
    }
}
