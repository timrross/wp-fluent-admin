# Checkbox Field

A labeled checkbox field that submits `1` when checked.

## Inside a FormTable

```php
use FluentAdmin\Components\FormTable;

echo FormTable::make()->checkbox('enabled', 'Enable integration', [
    'checked' => true,
    'description' => 'Turn on API synchronization.',
]);
```

## Standalone

```php
use FluentAdmin\Fields\CheckboxField;

echo CheckboxField::make('enabled', 'Enable integration')
    ->checked(true)
    ->description('Turn on API synchronization.');
```

**Renders:**

```html
<label><input type="checkbox" id="enabled" name="enabled" value="1" checked /> Enable integration</label>
<p class="description">Turn on API synchronization.</p>
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$name` | `string` | — | Field name attribute |
| `$label` | `string` | `''` | Label text shown next to checkbox |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $name, string $label = '')` | `static` | Factory constructor |
| `->checked(bool $checked = true)` | `static` | Set checked state |
| `->description(string $description)` | `static` | Add description paragraph |
| `->disabled()` | `static` | Set `disabled` attribute |
| `->render()` | `string` | Return rendered HTML |
