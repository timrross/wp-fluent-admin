<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Button;
use FluentAdmin\Components\ButtonGroup;
use PHPUnit\Framework\TestCase;

class ButtonGroupTest extends TestCase
{
    public function testWrapsInButtonGroupDiv(): void
    {
        $html = ButtonGroup::make()->render();
        $this->assertStringContainsString('class="button-group"', $html);
    }

    public function testAddRendersButtonsAsChildren(): void
    {
        $html = ButtonGroup::make()
            ->add(Button::make('Save'), Button::make('Cancel'))
            ->render();

        $this->assertStringContainsString('Save', $html);
        $this->assertStringContainsString('Cancel', $html);
    }

    public function testChildCallbackCaptured(): void
    {
        $html = ButtonGroup::make()
            ->child(function () {
                echo '<span>custom</span>';
            })
            ->render();

        $this->assertStringContainsString('<span>custom</span>', $html);
    }

    public function testChildrenMethodAddsMultiple(): void
    {
        $html = ButtonGroup::make()
            ->children([
                Button::make('A'),
                Button::make('B'),
            ])
            ->render();

        $this->assertStringContainsString('>A<', $html);
        $this->assertStringContainsString('>B<', $html);
    }

    public function testStringChildrenAreEscaped(): void
    {
        $html = ButtonGroup::make()
            ->child('<script>alert(1)</script>')
            ->render();

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }
}
