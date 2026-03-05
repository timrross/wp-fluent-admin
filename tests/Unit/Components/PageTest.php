<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Page;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    public function testWrapsInWrapDiv(): void
    {
        $html = Page::make('My Page')->render();
        $this->assertStringContainsString('<div class="wrap">', $html);
    }

    public function testTitleInH1(): void
    {
        $html = Page::make('My Page')->render();
        $this->assertStringContainsString('<h1>', $html);
        $this->assertStringContainsString('My Page', $html);
    }

    public function testTitleIsEscaped(): void
    {
        $html = Page::make('<script>xss</script>')->render();
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function testIconRendersDashiconSpan(): void
    {
        $html = Page::make('Settings')->icon('dashicons-admin-settings')->render();
        $this->assertStringContainsString('dashicons-admin-settings', $html);
        $this->assertStringContainsString('<span class="dashicons', $html);
    }

    public function testContentCallableIsCaptured(): void
    {
        $html = Page::make('Page')
            ->content(function () {
                echo '<p>Hello from callback</p>';
            })
            ->render();

        $this->assertStringContainsString('<p>Hello from callback</p>', $html);
    }

    public function testRenderWithCallableEchoesOutput(): void
    {
        ob_start();
        $result = Page::make('Page')->render(function () {
            echo '<p>Direct render</p>';
        });
        $output = ob_get_clean();

        $this->assertStringContainsString('<p>Direct render</p>', $output);
        $this->assertSame('', $result);
    }

    public function testNoIconByDefault(): void
    {
        $html = Page::make('Page')->render();
        $this->assertStringNotContainsString('dashicons', $html);
    }

    public function testRenderWithCallableStillPassesThroughRenderFilter(): void
    {
        $GLOBALS['fluent_admin_applied_filters'] = [];

        ob_start();
        Page::make('Page')->render(function () {
            echo '<p>Filtered</p>';
        });
        ob_end_clean();

        $this->assertContains('fluent_admin_page_render', $GLOBALS['fluent_admin_applied_filters']);
    }

    public function testToStringWorks(): void
    {
        $page = Page::make('Test');
        $this->assertSame($page->render(), (string) $page);
    }
}
