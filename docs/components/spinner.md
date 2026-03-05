# Spinner

Renders a native WordPress loading spinner.

## Basic Usage

```php
use FluentAdmin\Components\Spinner;

echo Spinner::make();
```

**Renders:**

```html
<span class="spinner is-active"></span>
```

## Variants

### Inactive spinner

```php
echo Spinner::make(false);
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$active` | `bool` | `true` | Whether spinner includes `is-active` |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(bool $active = true)` | `static` | Factory constructor |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_spinner_render` | `string $html, array $config` | Modify rendered Spinner markup |

## Cookbook

### Show while async action runs

```php
echo '<button class="button">Sync</button>';
echo Spinner::make(false);
```

## WordPress Reference

Spinner uses wp-admin `.spinner` class.
See [AJAX in Plugins](https://developer.wordpress.org/plugins/javascript/ajax/).
