# Metabox

Renders a WordPress postbox (`.postbox`) with header and body content.
The component wraps the postbox in a `metabox-holder` container so WordPress core heading styles such as `.metabox-holder h2.hndle` apply even when the box is rendered outside a full post editor layout.

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
<div class="metabox-holder">
  <div class="postbox" id="my-plugin-api-settings">
    <div class="postbox-header"><h2 class="hndle">API Settings</h2></div>
    <div class="inside"><p>Enter your API key and endpoint.</p></div>
  </div>
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

### Standalone admin box

```php
use FluentAdmin\Components\{Button, Metabox, Page};

add_action('admin_menu', function () {
    add_menu_page('Example', 'Example', 'manage_options', 'standalone-box', function () {
        Page::make('Standalone Metabox')->render(function () {
            echo Metabox::make('Deployment Status')
                ->id('deployment-status')
                ->content(function () {
                    echo '<p>Last deploy completed successfully.</p>';
                    echo Button::make('View Logs', admin_url('tools.php?page=deployment-logs'))->small();
                });
        });
    });
});
```

Use this pattern when you want postbox styling on a simple settings or tools screen without building a full `MetaboxContainer`.

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
| `->render()` | `string` | Return rendered HTML with a `metabox-holder` wrapper around the postbox |

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

Metabox uses the same `postbox` structure as classic wp-admin screens and includes a `metabox-holder` ancestor wrapper needed for core `h2.hndle` styling on standalone boxes.
See [Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/).
