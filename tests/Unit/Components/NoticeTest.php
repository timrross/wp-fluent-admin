<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Notice;
use PHPUnit\Framework\TestCase;

class NoticeTest extends TestCase
{
    public function testDefaultTypeRendersInfoClass(): void
    {
        $html = Notice::make('Hello')->render();
        $this->assertStringContainsString('notice-info', $html);
    }

    public function testSuccessType(): void
    {
        $html = Notice::make('OK', 'success')->render();
        $this->assertStringContainsString('notice-success', $html);
    }

    public function testWarningType(): void
    {
        $html = Notice::make('Careful', 'warning')->render();
        $this->assertStringContainsString('notice-warning', $html);
    }

    public function testErrorType(): void
    {
        $html = Notice::make('Broken', 'error')->render();
        $this->assertStringContainsString('notice-error', $html);
    }

    public function testDefaultTypeHasNoNoticeSuffix(): void
    {
        $html = Notice::make('Msg', 'default')->render();
        $this->assertStringNotContainsString('notice-default', $html);
        $this->assertStringContainsString('class="notice"', $html);
    }

    public function testDismissibleAddsDismissibleClass(): void
    {
        $html = Notice::make('Msg', 'info', true)->render();
        $this->assertStringContainsString('is-dismissible', $html);
    }

    public function testDismissibleFluentMethod(): void
    {
        $html = Notice::make('Msg')->dismissible()->render();
        $this->assertStringContainsString('is-dismissible', $html);
    }

    public function testNotDismissibleByDefault(): void
    {
        $html = Notice::make('Msg')->render();
        $this->assertStringNotContainsString('is-dismissible', $html);
    }

    public function testAltAddsAltClass(): void
    {
        $html = Notice::make('Msg')->alt()->render();
        $this->assertStringContainsString('notice-alt', $html);
    }

    public function testMessageIsEscaped(): void
    {
        $html = Notice::make('<script>xss()</script>')->render();
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function testMessageRenderedInParagraph(): void
    {
        $html = Notice::make('Hello World')->render();
        $this->assertStringContainsString('<p>Hello World</p>', $html);
    }

    public function testAlwaysHasNoticeBaseClass(): void
    {
        $html = Notice::make('Msg', 'success')->render();
        $this->assertStringContainsString('class="notice', $html);
    }

    public function testToStringWorks(): void
    {
        $notice = Notice::make('Test', 'info');
        $this->assertSame($notice->render(), (string) $notice);
    }
}
