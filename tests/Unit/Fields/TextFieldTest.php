<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Fields;

use FluentAdmin\Fields\TextField;
use PHPUnit\Framework\TestCase;

class TextFieldTest extends TestCase
{
    public function testRendersInputWithTypeText(): void
    {
        $html = TextField::make('email', 'Email')->render();
        $this->assertStringContainsString('type="text"', $html);
    }

    public function testRendersWithCorrectName(): void
    {
        $html = TextField::make('email', 'Email')->render();
        $this->assertStringContainsString('name="email"', $html);
    }

    public function testIdDefaultsToName(): void
    {
        $html = TextField::make('email', 'Email')->render();
        $this->assertStringContainsString('id="email"', $html);
    }

    public function testRendersValue(): void
    {
        $html = TextField::make('email', 'Email')->value('test@example.com')->render();
        $this->assertStringContainsString('value="test@example.com"', $html);
    }

    public function testValueIsEscaped(): void
    {
        $html = TextField::make('f', '')->value('<xss>')->render();
        $this->assertStringNotContainsString('<xss>', $html);
        $this->assertStringContainsString('&lt;xss&gt;', $html);
    }

    public function testDefaultSizeIsRegularText(): void
    {
        $html = TextField::make('f', '')->render();
        $this->assertStringContainsString('class="regular-text"', $html);
    }

    public function testSmallSizeClass(): void
    {
        $html = TextField::make('f', '')->size('small')->render();
        $this->assertStringContainsString('class="small-text"', $html);
    }

    public function testLargeSizeClass(): void
    {
        $html = TextField::make('f', '')->size('large')->render();
        $this->assertStringContainsString('class="large-text"', $html);
    }

    public function testPlaceholderAttribute(): void
    {
        $html = TextField::make('f', '')->placeholder('Enter text...')->render();
        $this->assertStringContainsString('placeholder="Enter text..."', $html);
    }

    public function testRequiredAttribute(): void
    {
        $html = TextField::make('f', '')->required()->render();
        $this->assertStringContainsString('required', $html);
    }

    public function testDisabledAttribute(): void
    {
        $html = TextField::make('f', '')->disabled()->render();
        $this->assertStringContainsString('disabled', $html);
    }

    public function testDescriptionRenderedAsP(): void
    {
        $html = TextField::make('f', '')->description('Help text')->render();
        $this->assertStringContainsString('<p class="description">Help text</p>', $html);
    }

    public function testDescriptionIsEscaped(): void
    {
        $html = TextField::make('f', '')->description('<b>bold</b>')->render();
        $this->assertStringNotContainsString('<b>', $html);
    }
}
