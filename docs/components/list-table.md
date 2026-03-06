# List Table

Renders a dynamic `WP_List_Table` with pagination, sorting, bulk actions, and optional search box.

For static tabular data with no `WP_List_Table` dependency, use [Data Table](/components/data-table).
When `WP_List_Table` is unavailable (for example in unit tests), `ListTable` renders a fallback paragraph.

## Basic Usage

```php
use FluentAdmin\Components\ListTable;

$rows = [
    ['id' => 1, 'email' => 'a@example.com', 'status' => 'active'],
    ['id' => 2, 'email' => 'b@example.com', 'status' => 'paused'],
];

echo ListTable::make()
    ->columns(['email' => 'Email', 'status' => 'Status'])
    ->count(function (): int { return 2; })
    ->data(function (array $args) use ($rows): array {
        return $rows;
    });
```

**Renders:**

```html
<table class="wp-list-table widefat fixed striped table-view-list">...</table>
```

## Variants

### Sortable columns

```php
echo ListTable::make()
    ->columns(['email' => 'Email', 'status' => 'Status'])
    ->sortable(['email', 'status'])
    ->count($countCb)
    ->data($dataCb);
```

### Bulk actions

Bulk actions require each row to include an `id` key — this is used as the checkbox value submitted as `bulk-ids[]`.
The surrounding `<form>` still needs to verify a nonce and process `action` / `action2` submissions.

```php
echo ListTable::make()
    ->columns(['email' => 'Email'])
    ->bulkActions(['delete' => 'Delete'])
    ->count($countCb)
    ->data($dataCb);
```

### Search box

`->search()` renders the standard `WP_List_Table` search UI. If you want actual filtering, read the submitted search term in your surrounding page/controller and apply it in your data layer before returning rows.

```php
echo ListTable::make()
    ->columns(['email' => 'Email'])
    ->search()
    ->count($countCb)
    ->data($dataCb);
```

### Row actions callback

Action markup is sanitized through `wp_kses_post` in WordPress — only post-safe HTML is preserved. For edit/delete links, plain `<a>` tags work as shown.
Row actions are appended to the table's primary column, which is usually the first non-checkbox column.

```php
echo ListTable::make()
    ->columns(['email' => 'Email'])
    ->rowActions(function (array $item): array {
        $id = (int) ($item['id'] ?? 0);

        return [
            'edit' => sprintf(
                '<a href="%s">Edit</a>',
                esc_url(admin_url('admin.php?page=my-plugin-edit&id=' . $id))
            ),
            'delete' => sprintf(
                '<a href="%s">Delete</a>',
                esc_url(admin_url('admin.php?page=my-plugin-delete&id=' . $id))
            ),
        ];
    })
    ->count($countCb)
    ->data($dataCb);
```

### Custom column rendering pattern

`ListTable` escapes cell values by default. To customize column output, compute formatted values in your `->data()` callback before returning rows.

```php
->data(function (array $args) use ($records): array {
    return array_map(function (array $r): array {
        $r['status'] = strtoupper((string) $r['status']);
        return $r;
    }, $records);
})
```

## API Reference

### Constructor

No constructor parameters.

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make()` | `static` | Factory constructor |
| `->columns(array $columns)` | `static` | Set column map (`key => label`) |
| `->sortable(array $columns)` | `static` | Set sortable column keys |
| `->bulkActions(array $actions)` | `static` | Set bulk action map (`key => label`) |
| `->perPage(int $count)` | `static` | Set items per page |
| `->data(callable $callback)` | `static` | Data callback (`fn(array $args): array`) |
| `->count(callable $callback)` | `static` | Total count callback (`fn(): int`) |
| `->rowActions(callable $callback)` | `static` | Row actions callback (`fn(array $item): array`) |
| `->search(bool $show = true)` | `static` | Toggle search box rendering |
| `->render()` | `string` | Return rendered HTML |

`$args` passed to `->data()` contains: `per_page`, `page`, `orderby`, `order`.

`->data()` is dual-purpose:

- `->data(callable $callback)` sets the table data callback.
- `->data(string $name, mixed $value)` sets a `data-*` attribute via `HasAttributes`.

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_listtable_render` | `string $html, array $config` | Modify rendered ListTable markup |

## Cookbook

### Server-side sorted table

```php
echo ListTable::make()
    ->columns(['email' => 'Email', 'created_at' => 'Created'])
    ->sortable(['email', 'created_at'])
    ->rowActions(function (array $item): array {
        $id = (int) ($item['id'] ?? 0);

        return [
            'delete' => sprintf(
                '<a href="%s">Delete</a>',
                esc_url(
                    wp_nonce_url(
                        admin_url('admin.php?page=my-plugin-users&action=delete&id=' . $id),
                        'delete_user_' . $id
                    )
                )
            ),
        ];
    })
    ->count(function (): int {
        return 120;
    })
    ->data(function (array $args) use ($repository): array {
        return $repository->fetchUsers($args);
    });
```

## WordPress Reference

ListTable is built on `WP_List_Table`.
See [`WP_List_Table`](https://developer.wordpress.org/reference/classes/wp_list_table/).
