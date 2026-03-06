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

`enqueueAssets()` loads the WordPress media library framework (`wp_enqueue_media()`), but the "Select Image" and "Remove" buttons require custom JavaScript to function. Add a handler using `wp.media()`:

```js
document.querySelectorAll('.fluent-admin-media-field').forEach(function (wrapper) {
    var input   = wrapper.querySelector('input[type="hidden"]');
    var preview = wrapper.querySelector('.fluent-admin-media-preview img');
    var frame;

    wrapper.querySelector('.fluent-admin-media-select').addEventListener('click', function () {
        if (frame) { frame.open(); return; }
        frame = wp.media({ title: 'Select Image', button: { text: 'Use this image' }, multiple: false });
        frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            input.value = attachment.id;
            preview.src = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
            preview.parentElement.style.display = '';
            wrapper.querySelector('.fluent-admin-media-remove').style.display = '';
        });
        frame.open();
    });

    wrapper.querySelector('.fluent-admin-media-remove').addEventListener('click', function () {
        input.value = '';
        preview.src = '';
        preview.parentElement.style.display = 'none';
        this.style.display = 'none';
    });
});
```

## Production example

```php
use FluentAdmin\Components\{Page, Metabox, FormTable, Button, Notice};
use FluentAdmin\Fields\MediaField;

add_action('admin_enqueue_scripts', function (string $hook): void {
    if ('toplevel_page_fa-branding' !== $hook) {
        return;
    }

    MediaField::enqueueAssets();
    wp_enqueue_script(
        'fa-branding-media',
        plugins_url('branding-media.js', __FILE__),
        ['jquery'],
        '1.0.0',
        true
    );
});

function fa_render_branding_page(): void
{
    $updated = isset($_GET['settings-updated'])
        && 'true' === sanitize_text_field(wp_unslash($_GET['settings-updated']));

    $settings = (array) get_option('fa_branding', ['logo_id' => 0]);

    Page::make('Branding')->render(function () use ($updated, $settings) {
        if ($updated) {
            echo Notice::make('Branding updated.', 'success')->dismissible();
        }

        echo '<form method="post" action="options.php">';
        settings_fields('fa_branding_group');

        echo Metabox::make('Brand Assets')->content(
            FormTable::make()->field(
                MediaField::make('fa_branding[logo_id]', 'Logo')
                    ->value((int) ($settings['logo_id'] ?? 0))
                    ->description('Recommended size: 512x512 pixels.')
            )
        );

        echo Button::make('Save Branding')->primary()->submit();
        echo '</form>';
    });
}
```

The current `MediaField` implementation renders `value()` and `description()`. It does not currently use `placeholder()`, `required()`, or `disabled()`.

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
| `::enqueueAssets()` | `void` | Call `wp_enqueue_media()`; you still need your own `wp.media()` button handlers |
| `->value(mixed $value)` | `static` | Set attachment ID |
| `->description(string $description)` | `static` | Add description paragraph |
| `->render()` | `string` | Return rendered HTML |
