# Metabox Container

Renders the WordPress two-column `poststuff` layout for primary and sidebar metabox stacks.

## Basic Usage

```php
use FluentAdmin\Components\{Metabox, MetaboxContainer};

echo MetaboxContainer::make()
    ->columns(2)
    ->primary(Metabox::make('Main')->content('Main content'))
    ->sidebar(Metabox::make('Sidebar')->content('Secondary content'));
```

**Renders:**

```html
<div id="poststuff">
  <div id="post-body" class="metabox-holder columns-2">
    <div id="post-body-content">...</div>
    <div id="postbox-container-1" class="postbox-container">...</div>
  </div>
</div>
```

## Variants

### One-column layout

```php
echo MetaboxContainer::make()
    ->columns(1)
    ->primary(Metabox::make('Only Column')->content('Content'));
```

### Callback content

```php
echo MetaboxContainer::make()->primary(function () {
    echo '<p>Rendered from a callback.</p>';
});
```

## API Reference

### Constructor

No constructor parameters.

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make()` | `static` | Factory constructor |
| `->columns(int $count = 2)` | `static` | Set `columns-{n}` class |
| `->primary(callable|Component|string $content)` | `static` | Set primary column content |
| `->sidebar(callable|Component|string $content)` | `static` | Set sidebar content |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_metaboxcontainer_render` | `string $html, array $config` | Modify rendered MetaboxContainer markup |

## Cookbook

### Main form + help sidebar

```php
echo MetaboxContainer::make()
    ->primary(Metabox::make('Settings')->content('Form goes here'))
    ->sidebar(Metabox::make('Help')->content('Support links'));
```

## WordPress Reference

MetaboxContainer matches the layout used by classic post editor admin screens.
See [Dashboard Widgets API](https://developer.wordpress.org/apis/handbook/dashboard-widgets/).
