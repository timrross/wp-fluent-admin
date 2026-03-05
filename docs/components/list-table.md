# List Table

Renders a dynamic `WP_List_Table` with pagination, sorting, bulk actions, and optional search box.

For static tabular data with no `WP_List_Table` dependency, use [Data Table](/components/data-table).

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

```php
echo ListTable::make()
    ->columns(['email' => 'Email'])
    ->bulkActions(['delete' => 'Delete'])
    ->count($countCb)
    ->data($dataCb);
```

### Search box

```php
echo ListTable::make()
    ->columns(['email' => 'Email'])
    ->search()
    ->count($countCb)
    ->data($dataCb);
```

### Row actions callback

```php
echo ListTable::make()
    ->columns(['email' => 'Email'])
    ->rowActions(function (array $item): array {
        return [
            'edit' => '<a href="admin.php?page=my-plugin-edit&id=' . (int) $item['id'] . '">Edit</a>',
            'delete' => '<a href="admin.php?page=my-plugin-delete&id=' . (int) $item['id'] . '">Delete</a>',
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
