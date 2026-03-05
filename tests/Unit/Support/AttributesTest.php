<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Support;

use FluentAdmin\Support\Attributes;
use PHPUnit\Framework\TestCase;

class AttributesTest extends TestCase
{
    public function testEmptyArrayReturnsEmptyString(): void
    {
        $this->assertSame('', Attributes::build([]));
    }

    public function testSimpleStringAttribute(): void
    {
        $this->assertSame(' id="my-id"', Attributes::build(['id' => 'my-id']));
    }

    public function testBooleanTrueRendersAttributeNameOnly(): void
    {
        $this->assertSame(' disabled', Attributes::build(['disabled' => true]));
    }

    public function testBooleanFalseOmitsAttribute(): void
    {
        $this->assertSame('', Attributes::build(['disabled' => false]));
    }

    public function testNullOmitsAttribute(): void
    {
        $this->assertSame('', Attributes::build(['hidden' => null]));
    }

    public function testClassArrayMergedIntoString(): void
    {
        $result = Attributes::build(['class' => ['foo', 'bar', 'baz']]);
        $this->assertSame(' class="foo bar baz"', $result);
    }

    public function testClassArrayFiltersEmpty(): void
    {
        $result = Attributes::build(['class' => ['foo', '', 'bar']]);
        $this->assertSame(' class="foo bar"', $result);
    }

    public function testEmptyClassStringOmitted(): void
    {
        $result = Attributes::build(['class' => '']);
        $this->assertSame('', $result);
    }

    public function testDataAttribute(): void
    {
        $result = Attributes::build(['data-action' => 'delete']);
        $this->assertSame(' data-action="delete"', $result);
    }

    public function testValuesAreEscaped(): void
    {
        $result = Attributes::build(['title' => '<script>xss</script>']);
        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('&lt;script&gt;', $result);
    }

    public function testMultipleAttributes(): void
    {
        $result = Attributes::build(['id' => 'my-input', 'type' => 'text', 'required' => true]);
        $this->assertStringContainsString('id="my-input"', $result);
        $this->assertStringContainsString('type="text"', $result);
        $this->assertStringContainsString('required', $result);
    }
}
