<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\FormTable;
use FluentAdmin\Fields\TextField;
use PHPUnit\Framework\TestCase;

class FormTableTest extends TestCase
{
    public function testRendersFormTableElement(): void
    {
        $html = FormTable::make()->render();
        $this->assertStringContainsString('class="form-table"', $html);
        $this->assertStringContainsString('role="presentation"', $html);
    }

    public function testRendersTableBodyTags(): void
    {
        $html = FormTable::make()->render();
        $this->assertStringContainsString('<tbody>', $html);
        $this->assertStringContainsString('</tbody>', $html);
    }

    public function testEachFieldGetsTableRow(): void
    {
        $html = FormTable::make()
            ->text('name', 'Name')
            ->text('email', 'Email')
            ->render();

        $this->assertSame(2, substr_count($html, '<tr>'));
    }

    public function testLabelHasCorrectForAttribute(): void
    {
        $html = FormTable::make()->text('api_key', 'API Key')->render();
        $this->assertStringContainsString('<label for="api_key">', $html);
    }

    public function testLabelTextRendered(): void
    {
        $html = FormTable::make()->text('f', 'My Label')->render();
        $this->assertStringContainsString('My Label', $html);
    }

    public function testDescriptionRenderedAsPClass(): void
    {
        $html = FormTable::make()
            ->text('f', 'Field', ['description' => 'Help text here'])
            ->render();

        $this->assertStringContainsString('<p class="description">Help text here</p>', $html);
    }

    public function testTextShortcutCreatesTextField(): void
    {
        $html = FormTable::make()->text('name', 'Name')->render();
        $this->assertStringContainsString('type="text"', $html);
    }

    public function testPasswordShortcutCreatesPasswordField(): void
    {
        $html = FormTable::make()->password('pw', 'Password')->render();
        $this->assertStringContainsString('type="password"', $html);
    }

    public function testTextareaShortcutCreatesTextarea(): void
    {
        $html = FormTable::make()->textarea('bio', 'Bio')->render();
        $this->assertStringContainsString('<textarea', $html);
    }

    public function testSelectShortcutCreatesSelect(): void
    {
        $html = FormTable::make()->select('region', 'Region', ['us' => 'US'])->render();
        $this->assertStringContainsString('<select', $html);
    }

    public function testCheckboxShortcutCreatesCheckbox(): void
    {
        $html = FormTable::make()->checkbox('active', 'Active')->render();
        $this->assertStringContainsString('type="checkbox"', $html);
    }

    public function testRadioShortcutCreatesRadio(): void
    {
        $html = FormTable::make()->radio('size', 'Size', ['sm' => 'Small'])->render();
        $this->assertStringContainsString('type="radio"', $html);
    }

    public function testFieldMethodAddsPrebuiltField(): void
    {
        $field = TextField::make('custom', 'Custom');
        $html = FormTable::make()->field($field)->render();
        $this->assertStringContainsString('name="custom"', $html);
    }

    public function testPlaceholderOptionPassedThrough(): void
    {
        $html = FormTable::make()->text('f', 'F', ['placeholder' => 'Type here'])->render();
        $this->assertStringContainsString('placeholder="Type here"', $html);
    }

    public function testValueOptionPassedThrough(): void
    {
        $html = FormTable::make()->text('f', 'F', ['value' => 'hello'])->render();
        $this->assertStringContainsString('value="hello"', $html);
    }

    public function testSizeOptionPassedThrough(): void
    {
        $html = FormTable::make()->text('f', 'F', ['size' => 'large'])->render();
        $this->assertStringContainsString('large-text', $html);
    }
}
