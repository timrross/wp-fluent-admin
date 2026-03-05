<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Card;
use FluentAdmin\Components\Notice;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function testRendersCardDiv(): void
    {
        $html = Card::make('Title')->render();
        $this->assertStringContainsString('<div class="card">', $html);
    }

    public function testTitleRenderedInH2(): void
    {
        $html = Card::make('My Card')->render();
        $this->assertStringContainsString('<h2 class="title">My Card</h2>', $html);
    }

    public function testTitleIsEscaped(): void
    {
        $html = Card::make('<script>xss</script>')->render();
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function testNoTitleSkipsH2(): void
    {
        $html = Card::make()->render();
        $this->assertStringNotContainsString('<h2', $html);
    }

    public function testStringContentRendered(): void
    {
        $html = Card::make('Card')->content('Body text')->render();
        $this->assertStringContainsString('Body text', $html);
    }

    public function testCallableContentCaptured(): void
    {
        $html = Card::make('Card')
            ->content(function () {
                echo '<p>Dynamic content</p>';
            })
            ->render();

        $this->assertStringContainsString('<p>Dynamic content</p>', $html);
    }

    public function testComponentContentRendered(): void
    {
        $notice = Notice::make('Info', 'info');
        $html = Card::make('Card')->content($notice)->render();
        $this->assertStringContainsString('notice-info', $html);
    }

    public function testFooterRendered(): void
    {
        $html = Card::make('Card')->footer('Footer text')->render();
        $this->assertStringContainsString('Footer text', $html);
    }

    public function testCallableFooterCaptured(): void
    {
        $html = Card::make('Card')
            ->footer(function () {
                echo '<small>Footer</small>';
            })
            ->render();

        $this->assertStringContainsString('<small>Footer</small>', $html);
    }
}
