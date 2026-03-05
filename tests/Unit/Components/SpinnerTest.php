<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Spinner;
use PHPUnit\Framework\TestCase;

class SpinnerTest extends TestCase
{
    public function testActiveSpinnerHasIsActiveClass(): void
    {
        $html = Spinner::make(true)->render();
        $this->assertStringContainsString('is-active', $html);
    }

    public function testInactiveSpinnerHasNoIsActiveClass(): void
    {
        $html = Spinner::make(false)->render();
        $this->assertStringNotContainsString('is-active', $html);
    }

    public function testDefaultIsActive(): void
    {
        $html = Spinner::make()->render();
        $this->assertStringContainsString('is-active', $html);
    }

    public function testRendersSpanElement(): void
    {
        $html = Spinner::make()->render();
        $this->assertStringContainsString('<span', $html);
        $this->assertStringContainsString('class="spinner', $html);
    }
}
