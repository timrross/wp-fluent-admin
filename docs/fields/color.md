# Color Field

A text input styled for the WordPress color picker (`wp-color-picker`).

This field requires WordPress color-picker assets to be enqueued.

## Inside a FormTable

```php
use FluentAdmin\Components\FormTable;
use FluentAdmin\Fields\ColorField;

add_action('admin_enqueue_scripts', function () {
    ColorField::enqueue();
});

echo FormTable::make()->field(
    ColorField::make('brand_color', 'Brand Color')->value('#2271b1')
);
```

## Standalone

```php
use FluentAdmin\Fields\ColorField;

add_action('admin_enqueue_scripts', function () {
    ColorField::enqueue();
});

echo ColorField::make('brand_color', 'Brand Color')
    ->value('#2271b1')
    ->description('Used for charts and highlights.');
```

**Renders:**

```html
<input type="text" class="wp-color-picker" id="brand_color" name="brand_color" value="#2271b1" />
<p class="description">Used for charts and highlights.</p>
```

## Enqueue Requirement

Call `ColorField::enqueue()` during `admin_enqueue_scripts` before rendering the field.

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$name` | `string` | — | Field name attribute |
| `$label` | `string` | `''` | Label text |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $name, string $label = '')` | `static` | Factory constructor |
| `::enqueue()` | `void` | Enqueue `wp-color-picker` script/style |
| `->value(mixed $value)` | `static` | Set color value |
| `->description(string $description)` | `static` | Add description paragraph |
| `->required()` | `static` | Set `required` attribute |
| `->disabled()` | `static` | Set `disabled` attribute |
| `->render()` | `string` | Return rendered HTML |
