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
- `->placeholder(string $placeholder)`
- `->required()`
- `->disabled()`
- `->getName()`
- `->getLabel()`
- `->getId()`

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
