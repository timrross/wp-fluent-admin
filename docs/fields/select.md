# Select Field

A dropdown field for choosing one value from an option list.

## Inside a FormTable

```php
use FluentAdmin\Components\FormTable;

echo FormTable::make()->select('region', 'Region', [
    'us' => 'United States',
    'eu' => 'Europe',
    'apac' => 'Asia Pacific',
], [
    'value' => 'eu',
    'description' => 'Choose deployment region.',
]);
```

## Standalone

```php
use FluentAdmin\Fields\SelectField;

echo SelectField::make('region', 'Region', [
    'us' => 'United States',
    'eu' => 'Europe',
    'apac' => 'Asia Pacific',
])->value('eu')->description('Choose deployment region.');
```

**Renders:**

```html
<select id="region" name="region">
  <option value="us">United States</option>
  <option value="eu" selected>Europe</option>
  <option value="apac">Asia Pacific</option>
</select>
<p class="description">Choose deployment region.</p>
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$name` | `string` | — | Field name attribute |
| `$label` | `string` | `''` | Label text |
| `$options` | `array<string|int,string>` | `[]` | Option map (`value => label`) |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $name, string $label = '', array $options = [])` | `static` | Factory constructor |
| `->options(array $options)` | `static` | Replace option map |
| `->value(mixed $value)` | `static` | Set selected option value |
| `->description(string $description)` | `static` | Add description paragraph |
| `->required()` | `static` | Set `required` attribute |
| `->disabled()` | `static` | Set `disabled` attribute |
| `->render()` | `string` | Return rendered HTML |
