# Filters

Every renderable component/field passes output through `apply_filters()` using this pattern:

`fluent_admin_{short_class_name_lowercase}_render`

All filters receive:

- `string $html`
- `array $config`

## Components

| Filter | Target |
|--------|--------|
| `fluent_admin_page_render` | `Page` |
| `fluent_admin_notice_render` | `Notice` |
| `fluent_admin_button_render` | `Button` |
| `fluent_admin_buttongroup_render` | `ButtonGroup` |
| `fluent_admin_metabox_render` | `Metabox` |
| `fluent_admin_metaboxcontainer_render` | `MetaboxContainer` |
| `fluent_admin_tabs_render` | `Tabs` |
| `fluent_admin_formtable_render` | `FormTable` |
| `fluent_admin_listtable_render` | `ListTable` |
| `fluent_admin_datatable_render` | `DataTable` |
| `fluent_admin_spinner_render` | `Spinner` |
| `fluent_admin_counter_render` | `Counter` |
| `fluent_admin_dashicon_render` | `Dashicon` |
| `fluent_admin_card_render` | `Card` |

## Fields

| Filter | Target |
|--------|--------|
| `fluent_admin_textfield_render` | `TextField` |
| `fluent_admin_textareafield_render` | `TextareaField` |
| `fluent_admin_selectfield_render` | `SelectField` |
| `fluent_admin_checkboxfield_render` | `CheckboxField` |
| `fluent_admin_radiofield_render` | `RadioField` |
| `fluent_admin_passwordfield_render` | `PasswordField` |
| `fluent_admin_colorfield_render` | `ColorField` |
| `fluent_admin_mediafield_render` | `MediaField` |

Custom field subclasses follow the same pattern (`CodeEditorField` => `fluent_admin_codeeditorfield_render`).

## Usage Example

```php
add_filter('fluent_admin_notice_render', function (string $html, array $config): string {
    return str_replace('notice', 'notice my-plugin-notice', $html);
}, 10, 2);
```

## Notes

- Filter names are derived from class short names (`ButtonGroup` => `buttongroup`).
- Use filters for small markup adjustments; prefer custom components for larger structural changes.
