# Password Field

A password input field rendered with WordPress `regular-text` styling.

## Inside a FormTable

```php
use FluentAdmin\Components\FormTable;

echo FormTable::make()->password('api_secret', 'API Secret', [
    'placeholder' => 'Enter secret',
    'description' => 'Stored securely in wp_options.',
]);
```

## Standalone

```php
use FluentAdmin\Fields\PasswordField;

echo PasswordField::make('api_secret', 'API Secret')
    ->placeholder('Enter secret')
    ->description('Stored securely in wp_options.');
```

**Renders:**

```html
<input type="password" id="api_secret" name="api_secret" value="" class="regular-text" placeholder="Enter secret" />
<p class="description">Stored securely in wp_options.</p>
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
| `->value(mixed $value)` | `static` | Set input value |
| `->placeholder(string $placeholder)` | `static` | Set placeholder attribute |
| `->description(string $description)` | `static` | Add description paragraph |
| `->required()` | `static` | Set `required` attribute |
| `->disabled()` | `static` | Set `disabled` attribute |
| `->render()` | `string` | Return rendered HTML |
