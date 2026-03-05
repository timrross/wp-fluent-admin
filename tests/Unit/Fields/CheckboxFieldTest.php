<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Fields;

use FluentAdmin\Fields\CheckboxField;
use PHPUnit\Framework\TestCase;

class CheckboxFieldTest extends TestCase
{
    public function testRendersCheckboxInput(): void
    {
        $html = CheckboxField::make('debug', 'Enable Debug')->render();
        $this->assertStringContainsString('type="checkbox"', $html);
    }

    public function testInputNameSet(): void
    {
        $html = CheckboxField::make('debug', 'Enable Debug')->render();
        $this->assertStringContainsString('name="debug"', $html);
    }

    public function testLabelWrapsInput(): void
    {
        $html = CheckboxField::make('debug', 'Enable Debug')->render();
        $this->assertStringContainsString('<label>', $html);
        $this->assertStringContainsString('</label>', $html);
    }

    public function testLabelTextIncluded(): void
    {
        $html = CheckboxField::make('debug', 'Enable Debug')->render();
        $this->assertStringContainsString('Enable Debug', $html);
    }

    public function testCheckedAttributeWhenSet(): void
    {
        $html = CheckboxField::make('debug', 'Enable Debug')->checked()->render();
        $this->assertStringContainsString('checked', $html);
    }

    public function testNotCheckedByDefault(): void
    {
        $html = CheckboxField::make('debug', 'Enable Debug')->render();
        $this->assertStringNotContainsString('checked', $html);
    }

    public function testCheckedFalseRemovesChecked(): void
    {
        $html = CheckboxField::make('debug', '')->checked(false)->render();
        $this->assertStringNotContainsString('checked', $html);
    }

    public function testValueIsAlways1(): void
    {
        $html = CheckboxField::make('debug', '')->render();
        $this->assertStringContainsString('value="1"', $html);
    }

    public function testDisabledAttribute(): void
    {
        $html = CheckboxField::make('debug', '')->disabled()->render();
        $this->assertStringContainsString('disabled', $html);
    }

    public function testLabelIsEscaped(): void
    {
        $html = CheckboxField::make('debug', '<b>Bold</b>')->render();
        $this->assertStringNotContainsString('<b>', $html);
    }
}
