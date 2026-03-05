# Page

Renders the top-level WordPress admin page wrapper (`.wrap`) with an `<h1>` title and callback content.

## Basic Usage

```php
use FluentAdmin\Components\Page;

Page::make('Plugin Settings')->render(function () {
    echo '<p>Settings content goes here.</p>';
});
```

**Renders:**

```html
<div class="wrap">
  <h1>Plugin Settings</h1>
  <p>Settings content goes here.</p>
</div>
```

## Variants

### With icon

```php
echo Page::make('Plugin Settings')
    ->icon('dashicons-admin-generic')
    ->content(function () {
        echo '<p>General options.</p>';
    })
    ->render();
```

### Build once, render later

```php
$page = Page::make('Reports')->content(function () {
    echo '<p>Monthly report.</p>';
});

echo $page;
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$title` | `string` | — | Page title, escaped automatically |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $title)` | `static` | Factory constructor |
| `->content(callable $callback)` | `static` | Set callback that renders page content |
| `->icon(string $dashicon)` | `static` | Add dashicon class before title |
| `->render(?callable $callback = null)` | `string` | Render HTML string; when callback is provided it echoes output and returns an empty string |
| `->toHtml()` | `string` | Alias of `render()` |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_page_render` | `string $html, array $config` | Modify rendered Page markup |

## Cookbook

### Use in `add_menu_page()` callback

```php
add_menu_page('My Plugin', 'My Plugin', 'manage_options', 'my-plugin', function () {
    Page::make('My Plugin')->icon('dashicons-admin-tools')->render(function () {
        echo '<p>Welcome.</p>';
    });
});
```

## WordPress Reference

Page mirrors the native admin page wrapper used in wp-admin screens.
See [Administration Menus](https://developer.wordpress.org/plugins/administration-menus/).
