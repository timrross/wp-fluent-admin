<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Button;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
    public function testDefaultRendersAnchorWithSecondaryClass(): void
    {
        $html = Button::make('Click me')->render();
        $this->assertStringContainsString('<a ', $html);
        $this->assertStringContainsString('class="button"', $html);
        $this->assertStringNotContainsString('button-primary', $html);
    }

    public function testPrimaryAddsButtonPrimaryClass(): void
    {
        $html = Button::make('Save')->primary()->render();
        $this->assertStringContainsString('button-primary', $html);
    }

    public function testHeroAddsButtonHeroClass(): void
    {
        $html = Button::make('Big')->hero()->render();
        $this->assertStringContainsString('button-hero', $html);
    }

    public function testSmallAddsButtonSmallClass(): void
    {
        $html = Button::make('Tiny')->small()->render();
        $this->assertStringContainsString('button-small', $html);
    }

    public function testSubmitRendersButtonElement(): void
    {
        $html = Button::make('Save')->submit()->render();
        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('type="submit"', $html);
        $this->assertStringNotContainsString('<a ', $html);
    }

    public function testDisabledAddsDisabledAttribute(): void
    {
        $html = Button::make('Nope')->disabled()->render();
        $this->assertStringContainsString('disabled', $html);
    }

    public function testUrlIsEscaped(): void
    {
        $html = Button::make('Go', '<script>bad</script>')->render();
        $this->assertStringNotContainsString('<script>', $html);
    }

    public function testTextIsEscaped(): void
    {
        $html = Button::make('<b>bold</b>')->render();
        $this->assertStringNotContainsString('<b>', $html);
        $this->assertStringContainsString('&lt;b&gt;', $html);
    }

    public function testDefaultUrlIsHash(): void
    {
        $html = Button::make('Click')->render();
        $this->assertStringContainsString('href="#"', $html);
    }

    public function testNewTabAddsTargetBlank(): void
    {
        $html = Button::make('Open')->newTab()->render();
        $this->assertStringContainsString('target="_blank"', $html);
    }

    public function testPrimaryAndHeroCombined(): void
    {
        $html = Button::make('Big Save')->primary()->hero()->render();
        $this->assertStringContainsString('button-primary', $html);
        $this->assertStringContainsString('button-hero', $html);
    }
}
