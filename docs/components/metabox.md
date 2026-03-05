# Metabox

Renders a WordPress postbox (`.postbox`) with header and body content.

## Basic Usage

```php
use FluentAdmin\Components\Metabox;

echo Metabox::make('API Settings')
    ->id('my-plugin-api-settings')
    ->content(function () {
        echo '<p>Enter your API key and endpoint.</p>';
    });
```

**Renders:**

```html
<div class="postbox" id="my-plugin-api-settings">
  <div class="postbox-header"><h2 class="hndle">API Settings</h2></div>
  <div class="inside"><p>Enter your API key and endpoint.</p></div>
</div>
```

## Variants

### Closed by default

```php
echo Metabox::make('Advanced')->closed()->content('Advanced fields.');
```

### Component content

```php
echo Metabox::make('Actions')->content(
    \FluentAdmin\Components\Button::make('Run Sync')->primary()
);
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$title` | `string` | — | Metabox title |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $title)` | `static` | Factory constructor |
| `->content(callable|Component|string $content)` | `static` | Set body content |
| `->id(string $id)` | `static` | Set postbox `id` attribute |
| `->closed()` | `static` | Add `closed` class |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_metabox_render` | `string $html, array $config` | Modify rendered Metabox markup |

## Cookbook

### Settings section in a form

```php
echo '<form method="post">';
echo Metabox::make('General Settings')->content(function () {
    echo '<p>Section content.</p>';
});
echo '</form>';
```

## WordPress Reference

Metabox uses the same `postbox` structure as classic wp-admin screens.
See [Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/).
