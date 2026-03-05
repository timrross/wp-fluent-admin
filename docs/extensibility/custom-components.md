# Custom Components

Extend `FluentAdmin\Component` to build reusable UI primitives that match your plugin domain.

## 1. Create a component class

```php
namespace MyPlugin\Admin;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

class StatusBadge extends Component
{
    protected string $label;
    protected string $type = 'info';

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    protected function html(): string
    {
        return '<span class="status-badge status-' . Escape::attr($this->type) . '">'
            . Escape::html($this->label)
            . '</span>';
    }
}
```

## 2. Use it in pages and metaboxes

```php
use FluentAdmin\Components\Page;
use MyPlugin\Admin\StatusBadge;

Page::make('Jobs')->render(function () {
    echo StatusBadge::make('Queued')->type('warning');
});
```

## 3. Hook into render filters

`Renderable` automatically exposes `fluent_admin_{classname}_render`.
For `StatusBadge`, the filter is `fluent_admin_statusbadge_render`.

```php
add_filter('fluent_admin_statusbadge_render', function (string $html, array $config): string {
    return str_replace('status-badge', 'status-badge my-plugin-badge', $html);
}, 10, 2);
```

## Notes

- Keep `html()` output-only. Avoid DB writes or request mutation in components.
- Escape all dynamic values with `FluentAdmin\Support\Escape`.
- Prefer fluent setters that return `static`.

See also: [Custom Components Guide](/guides/custom-components).
