# Textarea Field

A multi-line textarea field with configurable row count.

## Inside a FormTable

```php
use FluentAdmin\Components\FormTable;

echo FormTable::make()->textarea('notes', 'Notes', [
    'value' => 'Initial notes',
    'rows' => 6,
    'description' => 'Visible to admins only.',
]);
```

## Standalone

```php
use FluentAdmin\Fields\TextareaField;

echo TextareaField::make('notes', 'Notes')
    ->rows(6)
    ->value("Line one\nLine two")
    ->description('Visible to admins only.');
```

**Renders:**

```html
<textarea id="notes" name="notes" class="large-text" rows="6">Line one
Line two</textarea>
<p class="description">Visible to admins only.</p>
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
| `->rows(int $rows)` | `static` | Set textarea row count (default `5`) |
| `->value(mixed $value)` | `static` | Set textarea value |
| `->placeholder(string $placeholder)` | `static` | Set placeholder attribute |
| `->description(string $description)` | `static` | Add description paragraph |
| `->required()` | `static` | Set `required` attribute |
| `->disabled()` | `static` | Set `disabled` attribute |
| `->render()` | `string` | Return rendered HTML |
