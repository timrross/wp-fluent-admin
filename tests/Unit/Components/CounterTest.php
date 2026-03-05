<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Counter;
use PHPUnit\Framework\TestCase;

class CounterTest extends TestCase
{
    public function testDefaultRendersCountSpan(): void
    {
        $html = Counter::make(5)->render();
        $this->assertStringContainsString('class="count"', $html);
        $this->assertStringContainsString('(5)', $html);
    }

    public function testMenuStyleRendersUpdatePluginsSpan(): void
    {
        $html = Counter::make(3)->menuStyle()->render();
        $this->assertStringContainsString('update-plugins', $html);
        $this->assertStringContainsString('count-3', $html);
        $this->assertStringContainsString('plugin-count', $html);
    }

    public function testMenuStyleShowsCount(): void
    {
        $html = Counter::make(7)->menuStyle()->render();
        $this->assertStringContainsString('>7<', $html);
    }

    public function testZeroCount(): void
    {
        $html = Counter::make(0)->render();
        $this->assertStringContainsString('(0)', $html);
    }
}
