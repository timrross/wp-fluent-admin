# Fields Overview

Fields are standalone components that render form inputs and can also be composed inside [Form Table](/components/form-table).

## How fields work

- `Field` is the abstract base class for all field types.
- Each field renders its own input markup and optional description paragraph.
- Inside `FormTable`, each field is wrapped in a `<tr>` with a `<th>` label and `<td>` value cell.
- Standalone fields are useful for custom layouts outside `form-table`.

## Common fluent methods

All fields inherit these methods from `Field`:

- `->value(mixed $value)`
- `->description(string $description)`
- `->placeholder(string $placeholder)` — text, textarea, password only
- `->required()` — text, textarea, password, select, color only
- `->disabled()` — text, textarea, password, select, checkbox, radio, color only
- `->getName()`
- `->getLabel()`
- `->getId()`

Every field class exposes the inherited methods, but only fields that render the relevant HTML attribute will use them. For example, `placeholder()` affects text-like inputs, while `checked()` is specific to `CheckboxField`.

## Available fields

- [Text](/fields/text): single-line text input with size variants.
- [Textarea](/fields/textarea): multi-line text input.
- [Password](/fields/password): password input.
- [Select](/fields/select): dropdown select.
- [Checkbox](/fields/checkbox): labeled checkbox.
- [Radio](/fields/radio): grouped radio options.
- [Color](/fields/color): WordPress color picker field (`wp-color-picker`).
- [Media](/fields/media): WordPress media library chooser.

## Standalone example

```php
use FluentAdmin\Fields\TextField;

echo TextField::make('api_key', 'API Key')
    ->placeholder('pk_live_...')
    ->description('Paste your API key.');
```

## Production example

```php
use FluentAdmin\Components\FormTable;
use FluentAdmin\Fields\MediaField;

add_action('admin_enqueue_scripts', function (string $hook): void {
    if ('toplevel_page_fa-settings' !== $hook) {
        return;
    }

    MediaField::enqueueAssets();
});

$settings = (array) get_option('fa_settings', []);

echo FormTable::make()
    ->text('fa_settings[api_key]', 'API Key', [
        'value' => (string) ($settings['api_key'] ?? ''),
        'placeholder' => 'pk_live_...',
        'description' => 'Used for authenticated API calls.',
    ])
    ->select('fa_settings[region]', 'Region', [
        'us' => 'United States',
        'eu' => 'Europe',
    ], [
        'value' => (string) ($settings['region'] ?? 'us'),
    ])
    ->field(
        MediaField::make('fa_settings[logo_id]', 'Logo')
            ->value((int) ($settings['logo_id'] ?? 0))
            ->description('Shown in the top-right corner of the admin page.')
    );
```
