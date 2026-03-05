<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Fields;

use FluentAdmin\Fields\MediaField;
use PHPUnit\Framework\TestCase;

class MediaFieldTest extends TestCase
{
    public function testRendersHiddenInput(): void
    {
        $html = MediaField::make('image_id', 'Image')->render();
        $this->assertStringContainsString('type="hidden"', $html);
    }

    public function testIdAndNameAttributes(): void
    {
        $html = MediaField::make('image_id', 'Image')->render();
        $this->assertStringContainsString('id="image_id"', $html);
        $this->assertStringContainsString('name="image_id"', $html);
    }

    public function testRendersSelectButton(): void
    {
        $html = MediaField::make('image_id', 'Image')->render();
        $this->assertStringContainsString('Select Image', $html);
    }

    public function testRendersRemoveButton(): void
    {
        $html = MediaField::make('image_id', 'Image')->render();
        $this->assertStringContainsString('Remove', $html);
    }

    public function testRendersImagePreviewContainer(): void
    {
        $html = MediaField::make('image_id', 'Image')->render();
        $this->assertStringContainsString('fluent-admin-media-preview', $html);
    }

    public function testValueSetInHiddenInput(): void
    {
        $html = MediaField::make('image_id', 'Image')->value(42)->render();
        $this->assertStringContainsString('value="42"', $html);
    }

    public function testDescriptionRendered(): void
    {
        $html = MediaField::make('image_id', 'Image')->description('Choose a thumbnail')->render();
        $this->assertStringContainsString('Choose a thumbnail', $html);
    }
}
