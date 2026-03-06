# Notice

Renders a standard WordPress admin notice (`.notice`) for success, warning, error, or info feedback.

## Basic Usage

```php
use FluentAdmin\Components\Notice;

echo Notice::make('Settings saved.', 'success');
```

**Renders:**

```html
<div class="notice notice-success"><p>Settings saved.</p></div>
```

## Variants

### Dismissible

```php
echo Notice::make('Done.', 'success')->dismissible();
```

### Alt style

```php
echo Notice::make('Heads up.', 'warning')->alt();
```

### All types

```php
echo Notice::make('Info', 'info');
echo Notice::make('Saved', 'success');
echo Notice::make('Warning', 'warning');
echo Notice::make('Failed', 'error');
echo Notice::make('Neutral', 'default');
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$message` | `string` | — | Notice message text |
| `$type` | `string` | `'info'` | `info`, `success`, `warning`, `error`, `default` |
| `$dismissible` | `bool` | `false` | Show dismiss button state class |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $message, string $type = 'info', bool $dismissible = false)` | `static` | Factory constructor |
| `->dismissible(bool $value = true)` | `static` | Toggle `is-dismissible` class |
| `->alt(bool $value = true)` | `static` | Toggle `notice-alt` class |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_notice_render` | `string $html, array $config` | Modify rendered Notice markup |

## Cookbook

### Show on settings update

```php
$updated = isset($_GET['settings-updated'])
    && 'true' === sanitize_text_field(wp_unslash($_GET['settings-updated']));

if ($updated) {
    echo Notice::make('Settings saved.', 'success')->dismissible();
}
```

## WordPress Reference

Notice uses native `admin_notices` markup patterns.
See [`admin_notices`](https://developer.wordpress.org/reference/hooks/admin_notices/).
