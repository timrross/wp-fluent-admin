# Custom Fields

Extend `FluentAdmin\Fields\Field` to create new field types that work both standalone and inside `FormTable`.

## 1. Implement a field class

```php
namespace MyPlugin\Admin\Fields;

use FluentAdmin\Fields\Field;
use FluentAdmin\Support\Escape;

class CodeEditorField extends Field
{
    protected string $language = 'javascript';

    public function language(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    protected function inputHtml(): string
    {
        $id = Escape::attr($this->getId());
        $name = Escape::attr($this->getName());
        $value = Escape::textarea((string) $this->value);

        return '<textarea id="' . $id . '" name="' . $name . '" '
            . 'class="large-text code" rows="12" data-language="' . Escape::attr($this->language) . '">'
            . $value
            . '</textarea>';
    }
}
```

## 2. Use inside `FormTable`

```php
use FluentAdmin\Components\FormTable;
use MyPlugin\Admin\Fields\CodeEditorField;

echo FormTable::make()->field(
    CodeEditorField::make('webhook_script', 'Webhook Script')
        ->language('javascript')
        ->description('Executed after each sync.')
);
```

## 3. Use standalone

```php
echo CodeEditorField::make('transform', 'Transform Function')
    ->value('return data;')
    ->language('javascript');
```

## Notes

- `Field::html()` already appends description output.
- `FormTable` handles `<tr>`, `<th>`, and `<td>` wrapper markup.
- Keep your `inputHtml()` method focused on the input element only.
