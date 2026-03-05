# Radio Field

A grouped set of radio inputs for choosing one option.

## Inside a FormTable

```php
use FluentAdmin\Components\FormTable;

echo FormTable::make()->radio('mode', 'Mode', [
    'live' => 'Live',
    'test' => 'Test',
], [
    'value' => 'test',
    'description' => 'Use test mode in staging.',
]);
```

## Standalone

```php
use FluentAdmin\Fields\RadioField;

echo RadioField::make('mode', 'Mode', [
    'live' => 'Live',
    'test' => 'Test',
])->value('test')->description('Use test mode in staging.');
```

**Renders:**

```html
<fieldset>
  <label><input type="radio" id="mode_live" name="mode" value="live" /> Live</label><br>
  <label><input type="radio" id="mode_test" name="mode" value="test" checked /> Test</label><br>
</fieldset>
<p class="description">Use test mode in staging.</p>
```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$name` | `string` | — | Shared radio name |
| `$label` | `string` | `''` | Field label |
| `$options` | `array<string|int,string>` | `[]` | Option map (`value => label`) |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make(string $name, string $label = '', array $options = [])` | `static` | Factory constructor |
| `->options(array $options)` | `static` | Replace option map |
| `->value(mixed $value)` | `static` | Set selected option value |
| `->description(string $description)` | `static` | Add description paragraph |
| `->disabled()` | `static` | Set `disabled` attribute on options |
| `->render()` | `string` | Return rendered HTML |
