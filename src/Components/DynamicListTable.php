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
        $value = Escape::html((string) ($item[$column_name] ?? ''));

        if ($column_name !== $this->get_primary_column_name()) {
            return $value;
        }

        $actions = $this->resolveRowActions($item);
        if ($actions === []) {
            return $value;
        }

        return $value . $this->row_actions($actions);
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
            'orderby'  => $this->getQueryParam('orderby'),
            'order'    => $this->getQueryParam('order', 'asc'),
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

    /**
     * Resolve row actions for an item via configured callback.
     *
     * @param array<string, mixed> $item
     * @return array<string, string>
     */
    protected function resolveRowActions(array $item): array
    {
        $callback = $this->conf['row_actions_cb'] ?? null;
        if (!is_callable($callback)) {
            return [];
        }

        $actions = call_user_func($callback, $item);
        if (!is_array($actions)) {
            return [];
        }

        $sanitized = [];
        foreach ($actions as $key => $markup) {
            $actionKey = $this->sanitizeActionKey((string) $key);
            if ($actionKey === '') {
                continue;
            }

            $markupString = (string) $markup;
            if (function_exists('wp_kses_post')) {
                $sanitized[$actionKey] = (string) wp_kses_post($markupString);
                continue;
            }

            $sanitized[$actionKey] = strip_tags($markupString, '<a>');
        }

        return $sanitized;
    }

    /**
     * Sanitize a row action key for safe CSS class/name usage.
     *
     * @param string $key
     * @return string
     */
    protected function sanitizeActionKey(string $key): string
    {
        if (function_exists('sanitize_key')) {
            return (string) sanitize_key($key);
        }

        $key = strtolower($key);
        $key = preg_replace('/[^a-z0-9_\-]/', '', $key) ?? '';
        return $key;
    }

    /**
     * Safely read a scalar query parameter from $_GET.
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    protected function getQueryParam(string $key, string $default = ''): string
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only sort state from list table URL parameters.
        if (!isset($_GET[$key])) {
            return $default;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Value is unslashed and sanitized below.
        $value = $_GET[$key];
        $text = is_scalar($value) ? (string) $value : '';
        $text = $this->unslash($text);

        if (function_exists('sanitize_text_field')) {
            return sanitize_text_field($text);
        }

        return trim(strip_tags($text));
    }

    /**
     * Unslash a value using WordPress when available.
     *
     * @param string $value
     * @return string
     */
    protected function unslash(string $value): string
    {
        if (function_exists('wp_unslash')) {
            return (string) wp_unslash($value);
        }

        return stripslashes($value);
    }
}

// phpcs:enable PSR1.Files.SideEffects,PSR1.Methods.CamelCapsMethodName
