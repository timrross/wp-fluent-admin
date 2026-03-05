# Form Table

Renders a WordPress settings table (`.form-table`) and provides fluent shortcuts for all built-in field types.

## Basic Usage

```php
use FluentAdmin\Components\FormTable;

echo FormTable::make()
    ->text('api_key', 'API Key', ['placeholder' => 'pk_live_...'])
    ->select('environment', 'Environment', ['prod' => 'Production', 'dev' => 'Development'])
    ->checkbox('enabled', 'Enable integration', ['checked' => true]);
```

**Renders:**

```html
<table class="form-table" role="presentation">
  <tbody>
    <tr>
      <th scope="row"><label for="api_key">API Key</label></th>
      <td><input type="text" ... /></td>
    </tr>
  </tbody>
</table>
```

## Variants

### All shortcut methods

```php
echo FormTable::make()
    ->text('site_name', 'Site Name', ['value' => 'My Site'])
    ->password('api_secret', 'API Secret')
    ->textarea('notes', 'Notes', ['rows' => 6])
    ->select('region', 'Region', ['us' => 'US', 'eu' => 'EU'], ['value' => 'eu'])
    ->checkbox('enabled', 'Enable', ['checked' => true])
    ->radio('mode', 'Mode', ['live' => 'Live', 'test' => 'Test'], ['value' => 'test']);
```

### Add pre-built Field instance

```php
use FluentAdmin\Fields\TextField;

echo FormTable::make()->field(
    TextField::make('username', 'Username')->required()
);
```

## API Reference

### Constructor

No constructor parameters.

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make()` | `static` | Factory constructor |
| `->field(Field $field)` | `static` | Add pre-configured field instance |
| `->text(string $name, string $label, array $options = [])` | `static` | Add text field |
| `->password(string $name, string $label, array $options = [])` | `static` | Add password field |
| `->textarea(string $name, string $label, array $options = [])` | `static` | Add textarea field |
| `->select(string $name, string $label, array $choices, array $options = [])` | `static` | Add select field |
| `->checkbox(string $name, string $label, array $options = [])` | `static` | Add checkbox field |
| `->radio(string $name, string $label, array $choices, array $options = [])` | `static` | Add radio group field |
| `->render()` | `string` | Return rendered HTML |

`$options` supports `value`, `placeholder`, `description`, `required`, `disabled`.
`->textarea()` also supports `rows`, `->text()` supports `size`, and `->checkbox()` supports `checked`.

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_formtable_render` | `string $html, array $config` | Modify rendered FormTable markup |

## Cookbook

### Settings metabox body

```php
echo \FluentAdmin\Components\Metabox::make('General')->content(
    FormTable::make()
        ->text('app_name', 'App Name', ['value' => 'Fluent App'])
        ->radio('mode', 'Mode', ['prod' => 'Production', 'dev' => 'Development'], ['value' => 'prod'])
);
```

## WordPress Reference

FormTable uses native settings table markup.
See [Using Settings API](https://developer.wordpress.org/plugins/settings/using-settings-api/).
