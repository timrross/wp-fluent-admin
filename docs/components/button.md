# Button

Renders a WordPress admin button as either a link (`<a>`) or submit button (`<button type="submit">`).

Related: [Button Group](/components/button-group).

## Basic Usage

```php
use FluentAdmin\Components\Button;

echo Button::make('View Logs', admin_url('admin.php?page=my-plugin-logs'))->primary();
```

**Renders:**

```html
<a href="/wp-admin/admin.php?page=my-plugin-logs" class="button button-primary">View Logs</a>
```

## Variants

### Secondary, small, hero

```php
echo Button::make('Secondary')->secondary();
echo Button::make('Small')->small();
echo Button::make('Hero')->hero();
```

### Submit button

```php
echo Button::make('Save Settings')->primary()->submit();
```

### Disabled and new tab

```php
echo Button::make('Docs', 'https://example.com/docs')->newTab()->disabled();
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$text` | `string` | — | Button label |
| `$url` | `string` | `'#'` | Link URL for `<a>` variant |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $text, string $url = '#')` | `static` | Factory constructor |
| `->primary()` | `static` | Add `button-primary` class |
| `->secondary()` | `static` | Remove primary state |
| `->small()` | `static` | Add `button-small` class |
| `->hero()` | `static` | Add `button-hero` class |
| `->submit()` | `static` | Render `<button type="submit">` |
| `->disabled()` | `static` | Add `disabled` attribute |
| `->newTab()` | `static` | Add `target="_blank" rel="noopener noreferrer"` |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_button_render` | `string $html, array $config` | Modify rendered Button markup |

## Cookbook

### Settings form submit + secondary action

```php
echo Button::make('Save Settings')->primary()->submit();
echo Button::make('Back to list', admin_url('admin.php?page=my-plugin'))->secondary();
```

## WordPress Reference

Button uses core wp-admin button classes.
See [WordPress CSS Coding Standards (Forms/Buttons)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/).
