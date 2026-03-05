<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Support\Escape;

// phpcs:disable PSR1.Files.SideEffects,PSR1.Methods.CamelCapsMethodName

// Only define the class when WP_List_Table is available (i.e. inside WordPress).
// In unit tests this file is included but the class is not defined.
if (!class_exists('WP_List_Table')) {
    return;
}

/**
 * Internal config-driven WP_List_Table subclass.
 * Do not use directly — use ListTable instead.
 */
class DynamicListTable extends \WP_List_Table
{
    /** @var array<string, mixed> */
    protected array $conf;

    /**
     * @param array<string, mixed> $conf
     */
    public function __construct(array $conf)
    {
        $this->conf = $conf;
        parent::__construct([
            'singular' => 'item',
            'plural'   => 'items',
            'ajax'     => false,
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function get_columns(): array
    {
        $cols = [];

        if (!empty($this->conf['bulk'])) {
            $cols['cb'] = '<input type="checkbox" />';
        }

        foreach ($this->conf['columns'] as $key => $label) {
            $cols[$key] = $label;
        }

        return $cols;
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function get_sortable_columns(): array
    {
        $sortable = [];
        foreach ((array) ($this->conf['sortable'] ?? []) as $col) {
            $sortable[$col] = [$col, false];
        }
        return $sortable;
    }

    /**
     * @return array<string, string>
     */
    public function get_bulk_actions(): array
    {
        return (array) ($this->conf['bulk'] ?? []);
    }

    /**
     * @param array<string, mixed> $item
     * @param string               $column_name
     * @return string
     */
    public function column_default($item, $column_name): string
    {
        return Escape::html((string) ($item[$column_name] ?? ''));
    }

    /**
     * @param array<string, mixed> $item
     * @return string
     */
    public function column_cb($item): string
    {
        return sprintf(
            '<input type="checkbox" name="bulk-ids[]" value="%s" />',
            Escape::attr((string) ($item['id'] ?? ''))
        );
    }

    /**
     * @return void
     */
    public function prepare_items(): void
    {
        $this->_column_headers = [
            $this->get_columns(),
            [],
            $this->get_sortable_columns(),
        ];

        $perPage = (int) ($this->conf['per_page'] ?? 20);
        $currentPage = $this->get_pagenum();

        $args = [
            'per_page' => $perPage,
            'page'     => $currentPage,
            'orderby'  => isset($_GET['orderby']) ? sanitize_text_field((string) $_GET['orderby']) : '',
            'order'    => isset($_GET['order']) ? sanitize_text_field((string) $_GET['order']) : 'asc',
        ];

        $dataCb = $this->conf['data_cb'] ?? null;
        $countCb = $this->conf['count_cb'] ?? null;

        $this->items = is_callable($dataCb) ? (array) call_user_func($dataCb, $args) : [];
        $total = is_callable($countCb) ? (int) call_user_func($countCb) : 0;

        $this->set_pagination_args([
            'total_items' => $total,
            'per_page'    => $perPage,
        ]);
    }
}

// phpcs:enable PSR1.Files.SideEffects,PSR1.Methods.CamelCapsMethodName
