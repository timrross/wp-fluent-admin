# Tabs

Renders WordPress nav tabs (`.nav-tab-wrapper`) and displays content for the active tab based on the `tab` query arg.

## Basic Usage

```php
use FluentAdmin\Components\Tabs;

echo Tabs::make()
    ->tab('General', 'General settings content')
    ->tab('Advanced', 'Advanced settings content');
```

**Renders:**

```html
<h2 class="nav-tab-wrapper">
  <a href="...?tab=general" class="nav-tab nav-tab-active">General</a>
  <a href="...?tab=advanced" class="nav-tab">Advanced</a>
</h2>
<div class="tab-content">General settings content</div>
```

## Variants

### Fallback tab when no URL state

`->active()` sets which tab is shown when no `?tab=` query parameter is present in the URL. Once a user clicks a tab, the URL takes precedence and `->active()` has no effect.

```php
echo Tabs::make()
    ->tab('General', 'General settings')
    ->tab('Advanced', 'Advanced settings')
    ->active('Advanced');
```

### Component and callback content

```php
echo Tabs::make()
    ->tab('Overview', \FluentAdmin\Components\Card::make('Summary')->content('Stats'))
    ->tab('Logs', function () {
        echo '<p>Recent events.</p>';
    });
```

### URL-based state persistence

When a tab link is clicked, the URL includes `?tab={slug}` and that tab stays active on refresh.

Tab content passed as a plain string is escaped. Use a `Component` or callback when you want to render HTML markup.

## API Reference

### Constructor

No constructor parameters.

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make()` | `static` | Factory constructor |
| `->tab(string $label, callable|Component|string $content)` | `static` | Add a tab and its content |
| `->active(string $label)` | `static` | Set fallback active tab label |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_tabs_render` | `string $html, array $config` | Modify rendered Tabs markup |

## Cookbook

### Multi-section settings screen

```php
echo Tabs::make()
    ->tab('API', 'API settings')
    ->tab('Sync', 'Sync settings')
    ->tab('Advanced', 'Advanced settings')
    ->active('API');
```

## WordPress Reference

Tabs use the native `nav-tab` classes used in settings pages.
See [Settings API](https://developer.wordpress.org/plugins/settings/).
