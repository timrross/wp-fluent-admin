# Button Group

Wraps multiple Button components in WordPress `button-group` markup.

Related: [Button](/components/button).

## Basic Usage

```php
use FluentAdmin\Components\{Button, ButtonGroup};

echo ButtonGroup::make()->add(
    Button::make('Save')->primary()->submit(),
    Button::make('Cancel', admin_url('admin.php?page=my-plugin'))->secondary()
);
```

**Renders:**

```html
<div class="button-group">
  <button type="submit" class="button button-primary">Save</button>
  <a href="/wp-admin/admin.php?page=my-plugin" class="button">Cancel</a>
</div>
```

## Variants

### Add children one at a time

```php
$group = ButtonGroup::make();
$group->child(Button::make('Refresh'));
$group->child(Button::make('Export')->secondary());

echo $group;
```

## API Reference

### Constructor

No constructor parameters.

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make()` | `static` | Factory constructor |
| `->add(Button ...$buttons)` | `static` | Add one or more Button instances |
| `->child(Component|string|callable $child)` | `static` | Add generic child content |
| `->children(array $children)` | `static` | Add multiple children |
| `->render()` | `string` | Return rendered HTML |

## Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_buttongroup_render` | `string $html, array $config` | Modify rendered ButtonGroup markup |

## Cookbook

### Header actions row

```php
echo ButtonGroup::make()->add(
    Button::make('Add New', admin_url('admin.php?page=my-plugin-add'))->primary(),
    Button::make('Import CSV', admin_url('admin.php?page=my-plugin-import'))
);
```

## WordPress Reference

ButtonGroup follows the native `.button-group` pattern used in wp-admin controls.
See [WordPress Admin UI Patterns](https://developer.wordpress.org/).
