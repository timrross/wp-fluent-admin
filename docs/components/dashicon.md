# Dashicon

Renders a WordPress Dashicons `<span>` element.

## Basic Usage

```php
use FluentAdmin\Components\Dashicon;

echo Dashicon::make('admin-generic');
```

**Renders:**

```html
<span class="dashicons dashicons-admin-generic"></span>
```

## Variants

### Already prefixed icon name

```php
echo Dashicon::make('dashicons-admin-settings');
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$icon` | `string` | — | Dashicon slug with or without `dashicons-` prefix |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $icon)` | `static` | Factory constructor |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_dashicon_render` | `string $html, array $config` | Modify rendered Dashicon markup |

## Cookbook

### Prepend icon to section title

```php
echo Dashicon::make('chart-bar') . ' Reports';
```

## WordPress Reference

Dashicon output maps directly to Dashicons classes.
See [Dashicons Reference](https://developer.wordpress.org/resource/dashicons/).
