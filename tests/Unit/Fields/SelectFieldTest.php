<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Fields;

use FluentAdmin\Fields\SelectField;
use PHPUnit\Framework\TestCase;

class SelectFieldTest extends TestCase
{
    public function testRendersSelectElement(): void
    {
        $html = SelectField::make('region', 'Region', ['us' => 'US', 'eu' => 'EU'])->render();
        $this->assertStringContainsString('<select', $html);
        $this->assertStringContainsString('</select>', $html);
    }

    public function testRendersOptions(): void
    {
        $html = SelectField::make('region', 'Region', ['us' => 'US', 'eu' => 'EU'])->render();
        $this->assertStringContainsString('<option value="us">', $html);
        $this->assertStringContainsString('<option value="eu">', $html);
        $this->assertStringContainsString('US', $html);
        $this->assertStringContainsString('EU', $html);
    }

    public function testSelectedOptionMarked(): void
    {
        $html = SelectField::make('region', 'Region', ['us' => 'US', 'eu' => 'EU'])
            ->value('eu')
            ->render();

        $this->assertStringContainsString('<option value="eu" selected>', $html);
        $this->assertStringNotContainsString('<option value="us" selected>', $html);
    }

    public function testOptionValuesAreEscaped(): void
    {
        $html = SelectField::make('f', '', ['<xss>' => 'Bad'])->render();
        $this->assertStringNotContainsString('value="<xss>"', $html);
        $this->assertStringContainsString('&lt;xss&gt;', $html);
    }

    public function testOptionLabelsAreEscaped(): void
    {
        $html = SelectField::make('f', '', ['key' => '<b>Bold</b>'])->render();
        $this->assertStringNotContainsString('<b>', $html);
        $this->assertStringContainsString('&lt;b&gt;', $html);
    }

    public function testIdAndNameAttributes(): void
    {
        $html = SelectField::make('region', 'Region')->render();
        $this->assertStringContainsString('id="region"', $html);
        $this->assertStringContainsString('name="region"', $html);
    }

    public function testDisabledAttribute(): void
    {
        $html = SelectField::make('f', '', [])->disabled()->render();
        $this->assertStringContainsString('disabled', $html);
    }

    public function testOptionsMethodSetsOptions(): void
    {
        $html = SelectField::make('f', '')
            ->options(['a' => 'Alpha', 'b' => 'Beta'])
            ->render();

        $this->assertStringContainsString('Alpha', $html);
        $this->assertStringContainsString('Beta', $html);
    }
}
