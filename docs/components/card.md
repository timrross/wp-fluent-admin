# Card

Renders a simple wp-admin card container with optional title, body content, and footer content.

## Basic Usage

```php
use FluentAdmin\Components\Card;

echo Card::make('Status')
    ->content('All systems operational.')
    ->footer('Updated 2 minutes ago.');
```

**Renders:**

```html
<div class="card">
  <h2 class="title">Status</h2>
  All systems operational.
  Updated 2 minutes ago.
</div>
```

## Variants

### No title

```php
echo Card::make()->content('Body only card.');
```

### Component content

```php
echo Card::make('Actions')->content(
    \FluentAdmin\Components\Button::make('Run')->primary()
);
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$title` | `string` | `''` | Optional card title |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $title = '')` | `static` | Factory constructor |
| `->content(callable|Component|string $content)` | `static` | Set card body content |
| `->footer(callable|Component|string $footer)` | `static` | Set card footer content |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_card_render` | `string $html, array $config` | Modify rendered Card markup |

## Cookbook

### Dashboard stat card

```php
echo Card::make('Queued Jobs')
    ->content((string) \FluentAdmin\Components\Counter::make(9))
    ->footer('Worker running');
```

## WordPress Reference

Card uses the native `.card` container class.
See [Site Health admin UI](https://developer.wordpress.org/).
