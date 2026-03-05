<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Dashicon;
use PHPUnit\Framework\TestCase;

class DashiconTest extends TestCase
{
    public function testRendersSpanWithDashiconsClass(): void
    {
        $html = Dashicon::make('admin-settings')->render();
        $this->assertStringContainsString('<span class="dashicons', $html);
    }

    public function testPrefixesIconName(): void
    {
        $html = Dashicon::make('admin-settings')->render();
        $this->assertStringContainsString('dashicons-admin-settings', $html);
    }

    public function testDoesNotDoublePrefixIcon(): void
    {
        $html = Dashicon::make('dashicons-admin-settings')->render();
        $this->assertStringNotContainsString('dashicons-dashicons-', $html);
        $this->assertStringContainsString('dashicons-admin-settings', $html);
    }

    public function testIconNameIsEscaped(): void
    {
        $html = Dashicon::make('"><script>xss</script>')->render();
        $this->assertStringNotContainsString('<script>', $html);
    }
}
