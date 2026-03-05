# Media Field

A media picker field that stores an attachment ID in a hidden input and renders preview/select/remove controls.

This field requires WordPress media assets to be enqueued.

## Inside a FormTable

```php
use FluentAdmin\Components\FormTable;
use FluentAdmin\Fields\MediaField;

add_action('admin_enqueue_scripts', function () {
    MediaField::enqueueAssets();
});

echo FormTable::make()->field(
    MediaField::make('logo_id', 'Logo')->value(123)
);
```

## Standalone

```php
use FluentAdmin\Fields\MediaField;

add_action('admin_enqueue_scripts', function () {
    MediaField::enqueueAssets();
});

echo MediaField::make('logo_id', 'Logo')
    ->value(123)
    ->description('Choose a square logo image.');
```

**Renders:**

```html
<div class="fluent-admin-media-field" data-field-id="logo_id">
  <input type="hidden" id="logo_id" name="logo_id" value="123" />
  <div class="fluent-admin-media-preview"><img src="..." alt="" style="max-width:150px;max-height:150px;" /></div>
  <button type="button" class="button fluent-admin-media-select">Select Image</button>
  <button type="button" class="button fluent-admin-media-remove">Remove</button>
</div>
<p class="description">Choose a square logo image.</p>
```

## Enqueue Requirement

Call `MediaField::enqueueAssets()` during `admin_enqueue_scripts` before rendering the field.

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
| `::enqueueAssets()` | `void` | Call `wp_enqueue_media()` |
| `->value(mixed $value)` | `static` | Set attachment ID |
| `->description(string $description)` | `static` | Add description paragraph |
| `->render()` | `string` | Return rendered HTML |
