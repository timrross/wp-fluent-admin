<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;

/**
 * Renders a sortable, paginated WordPress list table via WP_List_Table.
 *
 * In unit tests (no WP), returns a fallback message.
 * In WordPress, instantiates DynamicListTable and captures display() output.
 */
class ListTable extends Component
{
    /** @var array<string, string> */
    protected array $columns = [];

    /** @var array<int, string> */
    protected array $sortableColumns = [];

    /** @var array<string, string> */
    protected array $bulkActionsMap = [];

    protected int $perPageCount = 20;

    /** @var callable|null */
    protected $dataCallback = null;

    /** @var callable|null */
    protected $countCallback = null;

    /** @var callable|null */
    protected $rowActionsCallback = null;

    protected bool $showSearch = false;

    /**
     * Set the table columns (key => label).
     *
     * @param array<string, string> $columns
     * @return static
     */
    public function columns(array $columns): static
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Set the sortable column keys.
     *
     * @param array<int, string> $columns
     * @return static
     */
    public function sortable(array $columns): static
    {
        $this->sortableColumns = $columns;
        return $this;
    }

    /**
     * Set bulk action label map (key => label).
     *
     * @param array<string, string> $actions
     * @return static
     */
    public function bulkActions(array $actions): static
    {
        $this->bulkActionsMap = $actions;
        return $this;
    }

    /**
     * Set the number of items per page.
     *
     * @param int $count
     * @return static
     */
    public function perPage(int $count): static
    {
        $this->perPageCount = $count;
        return $this;
    }

    /**
     * Set the data callback (receives query args array, returns rows array).
     *
     * @param callable $callback
     * @return static
     */
    public function data(callable $callback): static
    {
        $this->dataCallback = $callback;
        return $this;
    }

    /**
     * Set the total count callback (returns int).
     *
     * @param callable $callback
     * @return static
     */
    public function count(callable $callback): static
    {
        $this->countCallback = $callback;
        return $this;
    }

    /**
     * Set the row actions callback (receives row item, returns array of action links).
     *
     * @param callable $callback
     * @return static
     */
    public function rowActions(callable $callback): static
    {
        $this->rowActionsCallback = $callback;
        return $this;
    }

    /**
     * Show or hide the search box.
     *
     * @param bool $show
     * @return static
     */
    public function search(bool $show = true): static
    {
        $this->showSearch = $show;
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        if (!class_exists('FluentAdmin\\Components\\DynamicListTable')) {
            return '<p>WP_List_Table is not available.</p>';
        }

        $table = new DynamicListTable([
            'columns'   => $this->columns,
            'sortable'  => $this->sortableColumns,
            'bulk'      => $this->bulkActionsMap,
            'per_page'  => $this->perPageCount,
            'data_cb'   => $this->dataCallback,
            'count_cb'  => $this->countCallback,
        ]);

        $table->prepare_items();

        ob_start();

        if ($this->showSearch) {
            $table->search_box(__('Search'), 'search-input');
        }

        $table->display();

        return (string) ob_get_clean();
    }
}
