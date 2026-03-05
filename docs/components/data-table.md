# Data Table

Renders a static table with WordPress `wp-list-table widefat` styles.

Use this when you already have rows in memory and do not need `WP_List_Table` behavior. For pagination/search/sorting controls, use [List Table](/components/list-table).

## Basic Usage

```php
use FluentAdmin\Components\DataTable;

echo DataTable::make([
    'name' => 'Name',
    'email' => 'Email',
    'status' => 'Status',
])->rows([
    ['name' => 'Ada', 'email' => 'ada@example.com', 'status' => 'Active'],
    ['name' => 'Linus', 'email' => 'linus@example.com', 'status' => 'Invited'],
]);
```

**Renders:**

```html
<table class="wp-list-table widefat">
  <thead><tr><th>Name</th><th>Email</th><th>Status</th></tr></thead>
  <tbody>...</tbody>
</table>
```

## Variants

### Striped rows

```php
echo DataTable::make(['name' => 'Name'])->striped()->rows($rows);
```

### Narrow columns

```php
echo DataTable::make(['name' => 'Name', 'status' => 'Status'])->narrow()->rows($rows);
```

### Combined

```php
echo DataTable::make(['name' => 'Name', 'status' => 'Status'])
    ->striped()
    ->narrow()
    ->rows($rows);
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$headers` | `array<string,string>` | — | Column header map (`key => label`) |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(array $headers)` | `static` | Factory constructor |
| `->rows(array $rows)` | `static` | Set row data |
| `->striped(bool $value = true)` | `static` | Toggle `striped` class |
| `->narrow(bool $value = true)` | `static` | Toggle `narrow` class |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_datatable_render` | `string $html, array $config` | Modify rendered DataTable markup |

## Cookbook

### Recent activity card content

```php
echo \FluentAdmin\Components\Card::make('Recent Activity')->content(
    DataTable::make(['event' => 'Event', 'time' => 'Time'])
        ->striped()
        ->rows($events)
);
```

## WordPress Reference

DataTable uses core list table styling classes.
See [WordPress Admin CSS classes](https://developer.wordpress.org/).
