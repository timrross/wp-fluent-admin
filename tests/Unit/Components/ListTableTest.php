<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\ListTable;
use PHPUnit\Framework\TestCase;

class ListTableTest extends TestCase
{
    public function testDataMethodAcceptsCallable(): void
    {
        $result = ListTable::make()->data(
            static function (array $args): array {
                return [];
            }
        );

        $this->assertInstanceOf(ListTable::class, $result);
    }

    public function testDataMethodCanStillSetDataAttribute(): void
    {
        $result = ListTable::make()->data('action', 'sync');

        $this->assertInstanceOf(ListTable::class, $result);
    }

    public function testRenderReturnsFallbackWithoutDynamicListTable(): void
    {
        $html = ListTable::make()
            ->columns(['title' => 'Title'])
            ->data(
                static function (array $args): array {
                    return [];
                }
            )
            ->count(
                static function (): int {
                    return 0;
                }
            )
            ->search()
            ->render();

        $this->assertStringContainsString('WP_List_Table is not available.', $html);
    }
}
