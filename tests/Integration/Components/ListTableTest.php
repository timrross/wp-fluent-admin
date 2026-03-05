<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Integration\Components;

use FluentAdmin\Components\ListTable;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 */
class ListTableTest extends TestCase
{
    protected function setUp(): void
    {
        if (!class_exists('WP_List_Table')) {
            $this->markTestSkipped('WP_List_Table is not available — run integration tests inside WordPress.');
        }
    }

    public function testRendersWpListTable(): void
    {
        $html = ListTable::make()
            ->columns(['title' => 'Title', 'date' => 'Date'])
            ->data(function (array $args): array {
                return [
                    ['id' => 1, 'title' => 'Post One', 'date' => '2024-01-01'],
                ];
            })
            ->count(function (): int {
                return 1;
            })
            ->render();

        $this->assertStringContainsString('wp-list-table', $html);
    }
}
