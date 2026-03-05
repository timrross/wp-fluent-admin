# Counter

Renders a numeric badge for counts in admin UI.

## Basic Usage

```php
use FluentAdmin\Components\Counter;

echo Counter::make(5);
```

**Renders:**

```html
<span class="count">(5)</span>
```

## Variants

### Menu bubble style

```php
echo Counter::make(12)->menuStyle();
```

**Renders:**

```html
<span class="update-plugins count-12"><span class="plugin-count">12</span></span>
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$count` | `int` | — | Number to display |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(int $count)` | `static` | Factory constructor |
| `->menuStyle()` | `static` | Use update bubble variant |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_counter_render` | `string $html, array $config` | Modify rendered Counter markup |

## Cookbook

### Pending items in card header

```php
echo 'Pending ' . Counter::make(3);
```

## WordPress Reference

Counter maps to native count/update classes used in wp-admin menus.
See [Administration Menus](https://developer.wordpress.org/plugins/administration-menus/).
