# Text Field

A single-line text input field.

## Inside a FormTable

```php
use FluentAdmin\Components\FormTable;

echo FormTable::make()
    ->text('site_name', 'Site Name', [
        'value' => 'Acme Plugin',
        'placeholder' => 'My Plugin',
        'description' => 'Displayed in the dashboard.',
    ]);
```

## Standalone

```php
use FluentAdmin\Fields\TextField;

echo TextField::make('site_name', 'Site Name')
    ->value('Acme Plugin')
    ->placeholder('My Plugin')
    ->size('large')
    ->description('Displayed in the dashboard.');
```

**Renders:**

```html
<input type="text" id="site_name" name="site_name" value="Acme Plugin" class="large-text" placeholder="My Plugin" />
<p class="description">Displayed in the dashboard.</p>
```

## Size variants

```php
TextField::make('short')->size('small');   // small-text
TextField::make('normal')->size('regular'); // regular-text
TextField::make('wide')->size('large');    // large-text
```

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
| `->size(string $size)` | `static` | `small`, `regular`, or `large` |
| `->value(mixed $value)` | `static` | Set input value |
| `->placeholder(string $placeholder)` | `static` | Set placeholder attribute |
| `->description(string $description)` | `static` | Add description paragraph |
| `->required()` | `static` | Set `required` attribute |
| `->disabled()` | `static` | Set `disabled` attribute |
| `->render()` | `string` | Return rendered HTML |
