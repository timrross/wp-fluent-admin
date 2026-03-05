<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Tabs;
use PHPUnit\Framework\TestCase;

class TabsTest extends TestCase
{
    protected function setUp(): void
    {
        // Ensure clean $_GET state for each test
        unset($_GET['tab']);
    }

    public function testRendersNavTabWrapper(): void
    {
        $html = Tabs::make()->tab('General', 'Content')->render();
        $this->assertStringContainsString('nav-tab-wrapper', $html);
    }

    public function testEachTabHasNavTabClass(): void
    {
        $html = Tabs::make()
            ->tab('General', 'Content A')
            ->tab('Advanced', 'Content B')
            ->render();

        // Count <a> anchor tags — each tab gets one
        $count = substr_count($html, '<a ');
        $this->assertSame(2, $count);
    }

    public function testFirstTabActiveByDefault(): void
    {
        $html = Tabs::make()
            ->tab('General', 'A')
            ->tab('Advanced', 'B')
            ->render();

        $this->assertStringContainsString('nav-tab-active', $html);
        // The active tab link should contain the first tab label
        $this->assertMatchesRegularExpression('/nav-tab-active[^>]*>General/', $html);
    }

    public function testActiveTabContentRendered(): void
    {
        $html = Tabs::make()
            ->tab('General', 'General Content Here')
            ->tab('Advanced', 'Advanced Content Here')
            ->render();

        $this->assertStringContainsString('General Content Here', $html);
    }

    public function testInactiveTabContentNotRendered(): void
    {
        $html = Tabs::make()
            ->tab('General', 'General Content Here')
            ->tab('Advanced', 'Advanced Content Here')
            ->render();

        $this->assertStringNotContainsString('Advanced Content Here', $html);
    }

    public function testGetParamSetsActiveTab(): void
    {
        $_GET['tab'] = 'advanced';

        $html = Tabs::make()
            ->tab('General', 'General Content')
            ->tab('Advanced', 'Advanced Content')
            ->render();

        $this->assertStringContainsString('Advanced Content', $html);
        $this->assertStringNotContainsString('General Content', $html);

        unset($_GET['tab']);
    }

    public function testGetParamIsUnslashedBeforeSlugMatching(): void
    {
        $_GET['tab'] = 'advanced\\ tab';

        $html = Tabs::make()
            ->tab('General', 'General Content')
            ->tab('Advanced Tab', 'Advanced Tab Content')
            ->render();

        $this->assertStringContainsString('Advanced Tab Content', $html);
        $this->assertStringNotContainsString('General Content', $html);

        unset($_GET['tab']);
    }

    public function testTabSlugIsSanitized(): void
    {
        $html = Tabs::make()
            ->tab('My Settings!', 'Content')
            ->render();

        // The href slug should be sanitized (special chars stripped/replaced)
        $this->assertStringContainsString('tab=my-settings', $html);
        // The label text is still shown in the link (escaped for HTML safety)
        $this->assertStringContainsString('My Settings!', $html);
    }

    public function testTabLabelsAreEscaped(): void
    {
        $html = Tabs::make()
            ->tab('<script>xss</script>', 'Content')
            ->render();

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function testCallableContentCaptured(): void
    {
        $html = Tabs::make()
            ->tab('General', function () {
                echo '<p>Callable output</p>';
            })
            ->render();

        $this->assertStringContainsString('<p>Callable output</p>', $html);
    }

    public function testActiveMethodSetsDefaultTab(): void
    {
        $html = Tabs::make()
            ->tab('General', 'Gen Content')
            ->tab('Advanced', 'Adv Content')
            ->active('Advanced')
            ->render();

        $this->assertStringContainsString('Adv Content', $html);
        $this->assertStringNotContainsString('Gen Content', $html);
    }

    public function testEmptyTabsReturnsEmpty(): void
    {
        $html = Tabs::make()->render();
        $this->assertSame('', $html);
    }
}
