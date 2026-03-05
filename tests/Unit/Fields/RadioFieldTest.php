<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Fields;

use FluentAdmin\Fields\RadioField;
use PHPUnit\Framework\TestCase;

class RadioFieldTest extends TestCase
{
    public function testRendersFieldset(): void
    {
        $html = RadioField::make('color', 'Color', ['red' => 'Red', 'blue' => 'Blue'])->render();
        $this->assertStringContainsString('<fieldset>', $html);
        $this->assertStringContainsString('</fieldset>', $html);
    }

    public function testEachOptionRendersAsRadioInput(): void
    {
        $html = RadioField::make('color', 'Color', ['red' => 'Red', 'blue' => 'Blue'])->render();
        $this->assertStringContainsString('type="radio"', $html);
    }

    public function testCorrectNameGrouping(): void
    {
        $html = RadioField::make('color', 'Color', ['red' => 'Red', 'blue' => 'Blue'])->render();
        $count = substr_count($html, 'name="color"');
        $this->assertSame(2, $count);
    }

    public function testOptionLabelsRendered(): void
    {
        $html = RadioField::make('color', 'Color', ['red' => 'Red', 'blue' => 'Blue'])->render();
        $this->assertStringContainsString('Red', $html);
        $this->assertStringContainsString('Blue', $html);
    }

    public function testCheckedStateSetOnMatchingValue(): void
    {
        $html = RadioField::make('color', 'Color', ['red' => 'Red', 'blue' => 'Blue'])
            ->value('blue')
            ->render();

        $this->assertStringContainsString('value="blue" checked', $html);
        $this->assertStringNotContainsString('value="red" checked', $html);
    }

    public function testOptionValuesAreEscaped(): void
    {
        $html = RadioField::make('f', '', ['<xss>' => 'Bad'])->render();
        $this->assertStringNotContainsString('value="<xss>"', $html);
    }

    public function testOptionsMethodSetsOptions(): void
    {
        $html = RadioField::make('size', '')
            ->options(['sm' => 'Small', 'lg' => 'Large'])
            ->render();

        $this->assertStringContainsString('Small', $html);
        $this->assertStringContainsString('Large', $html);
    }
}
