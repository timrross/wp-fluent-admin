<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Renders a simple static data table using WordPress list table styles.
 *
 * Output: <table class="wp-list-table widefat [striped] [narrow]">
 *           <thead><tr>{headers}</tr></thead>
 *           <tbody>{rows}</tbody>
 *         </table>
 */
class DataTable extends Component
{
    /** @var array<string, string> Key => label header pairs */
    protected array $headers;

    /** @var array<int, array<string, mixed>> */
    protected array $rowData = [];

    protected bool $striped = false;
    protected bool $narrow = false;

    /**
     * @param array<string, string> $headers Key => label column header pairs.
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Set the table rows data.
     *
     * @param array<int, array<string, mixed>> $rows
     * @return static
     */
    public function rows(array $rows): static
    {
        $this->rowData = $rows;
        return $this;
    }

    /**
     * Enable/disable the striped row style.
     *
     * @param bool $value
     * @return static
     */
    public function striped(bool $value = true): static
    {
        $this->striped = $value;
        return $this;
    }

    /**
     * Enable/disable the narrow column style.
     *
     * @param bool $value
     * @return static
     */
    public function narrow(bool $value = true): static
    {
        $this->narrow = $value;
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $classes = ['wp-list-table', 'widefat'];

        if ($this->striped) {
            $classes[] = 'striped';
        }
        if ($this->narrow) {
            $classes[] = 'narrow';
        }

        $class = Escape::attr(implode(' ', $classes));

        // Headers
        $headerCells = '';
        foreach ($this->headers as $label) {
            $headerCells .= '<th>' . Escape::html((string) $label) . '</th>';
        }

        // Rows
        $bodyRows = '';
        foreach ($this->rowData as $row) {
            $cells = '';
            foreach (array_keys($this->headers) as $key) {
                $cellValue = isset($row[$key]) ? (string) $row[$key] : '';
                $cells .= '<td>' . Escape::html($cellValue) . '</td>';
            }
            $bodyRows .= '<tr>' . $cells . '</tr>';
        }

        return '<table class="' . $class . '">'
            . '<thead><tr>' . $headerCells . '</tr></thead>'
            . '<tbody>' . $bodyRows . '</tbody>'
            . '</table>';
    }
}
