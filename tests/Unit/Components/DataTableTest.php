<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\DataTable;
use PHPUnit\Framework\TestCase;

class DataTableTest extends TestCase
{
    public function testRendersTableWithWpListTableClasses(): void
    {
        $html = DataTable::make(['col' => 'Column'])->render();
        $this->assertStringContainsString('wp-list-table', $html);
        $this->assertStringContainsString('widefat', $html);
    }

    public function testRendersHeaderCells(): void
    {
        $html = DataTable::make(['name' => 'Name', 'email' => 'Email'])->render();
        $this->assertStringContainsString('<th>Name</th>', $html);
        $this->assertStringContainsString('<th>Email</th>', $html);
    }

    public function testRendersBodyRows(): void
    {
        $html = DataTable::make(['name' => 'Name'])
            ->rows([['name' => 'Alice'], ['name' => 'Bob']])
            ->render();

        $this->assertStringContainsString('<td>Alice</td>', $html);
        $this->assertStringContainsString('<td>Bob</td>', $html);
    }

    public function testStripedClassAdded(): void
    {
        $html = DataTable::make(['col' => 'Col'])->striped()->render();
        $this->assertStringContainsString('striped', $html);
    }

    public function testNarrowClassAdded(): void
    {
        $html = DataTable::make(['col' => 'Col'])->narrow()->render();
        $this->assertStringContainsString('narrow', $html);
    }

    public function testNoStripedByDefault(): void
    {
        $html = DataTable::make(['col' => 'Col'])->render();
        $this->assertStringNotContainsString('"striped"', $html);
        $this->assertStringNotContainsString(' striped', $html);
    }

    public function testCellContentIsEscaped(): void
    {
        $html = DataTable::make(['col' => 'Col'])
            ->rows([['col' => '<script>xss</script>']])
            ->render();

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function testMissingCellKeyRendersEmpty(): void
    {
        $html = DataTable::make(['name' => 'Name', 'email' => 'Email'])
            ->rows([['name' => 'Alice']])
            ->render();

        $this->assertStringContainsString('<td>Alice</td>', $html);
        $this->assertStringContainsString('<td></td>', $html);
    }

    public function testHeaderLabelsAreEscaped(): void
    {
        $html = DataTable::make(['col' => '<b>Bold</b>'])->render();
        $this->assertStringNotContainsString('<b>', $html);
    }
}
