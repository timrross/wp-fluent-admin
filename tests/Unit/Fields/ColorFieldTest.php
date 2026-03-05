<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Fields;

use FluentAdmin\Fields\ColorField;
use PHPUnit\Framework\TestCase;

class ColorFieldTest extends TestCase
{
    public function testRendersTextInput(): void
    {
        $html = ColorField::make('color', 'Colour')->render();
        $this->assertStringContainsString('type="text"', $html);
    }

    public function testHasWpColorPickerClass(): void
    {
        $html = ColorField::make('color', 'Colour')->render();
        $this->assertStringContainsString('class="wp-color-picker"', $html);
    }

    public function testIdAndNameAttributes(): void
    {
        $html = ColorField::make('brand_color', 'Brand Color')->render();
        $this->assertStringContainsString('id="brand_color"', $html);
        $this->assertStringContainsString('name="brand_color"', $html);
    }

    public function testValueAttribute(): void
    {
        $html = ColorField::make('color', '')->value('#ff0000')->render();
        $this->assertStringContainsString('value="#ff0000"', $html);
    }

    public function testValueIsEscaped(): void
    {
        $html = ColorField::make('color', '')->value('"><script>xss</script>')->render();
        $this->assertStringNotContainsString('<script>', $html);
    }

    public function testDisabledAttribute(): void
    {
        $html = ColorField::make('color', '')->disabled()->render();
        $this->assertStringContainsString('disabled', $html);
    }

    public function testDescriptionRendered(): void
    {
        $html = ColorField::make('color', '')->description('Pick a colour')->render();
        $this->assertStringContainsString('Pick a colour', $html);
    }
}
