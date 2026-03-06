# wp-fluent-admin

A fluent PHP component library for WordPress admin pages.
Build native-looking admin UI without hand-coding HTML boilerplate.

Documentation can be found here: <a href="https://timrross.github.io/wp-fluent-admin/">WP Fluent Admin docs</a>.

---

## Before and After

**Before — 47 lines of boilerplate:**

```php
<div class="wrap">
    <h1>My Plugin</h1>
    <?php if (isset($_GET['settings-updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>Settings saved.</p>
        </div>
    <?php endif; ?>
    <div class="postbox">
        <div class="postbox-header">
            <h2>API Settings</h2>
        </div>
        <div class="inside">
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="api_key">API Key</label></th>
                    <td><input type="text" id="api_key" name="api_key"
                               class="regular-text" value="<?php echo esc_attr($key); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="environment">Environment</label></th>
                    <td>
                        <select id="environment" name="environment">
                            <option value="prod" <?php selected($env, 'prod'); ?>>Production</option>
                            <option value="dev" <?php selected($env, 'dev'); ?>>Development</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
```

**After — 12 lines with wp-fluent-admin:**

```php
use FluentAdmin\Components\{Page, Notice, Metabox, FormTable};

Page::make('My Plugin')->render(function () {
    if (isset($_GET['settings-updated'])) {
        echo Notice::make('Settings saved.', 'success')->dismissible();
    }

    echo Metabox::make('API Settings')->content(
        FormTable::make()
            ->text('api_key', 'API Key')
            ->select('environment', 'Environment', ['prod' => 'Production', 'dev' => 'Development'])
    );
});
```

---

## Installation

```bash
composer require wp-fluent-admin/wp-fluent-admin
```

In your plugin's main file:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

No configuration, no service providers, no hooks to register.

**Requirements:** PHP 8.0+, WordPress 6.0+, Composer.

---

## Quick Start

### 1. Simple Page with Notice and Button

```php
use FluentAdmin\Components\{Page, Notice, Button};

add_menu_page('My Plugin', 'My Plugin', 'manage_options', 'my-plugin', function () {
    Page::make('My Plugin')->render(function () {
        echo Notice::make('Welcome!', 'info');
        echo Button::make('Get Started', '#')->primary();
    });
});
```

### 2. Settings Form

```php
use FluentAdmin\Components\{Page, Notice, Metabox, FormTable, Button};

add_action('admin_menu', function () {
    add_menu_page('Settings', 'My Plugin', 'manage_options', 'my-plugin-settings', function () {
        Page::make('My Plugin Settings')->render(function () {
            if (isset($_GET['settings-updated'])) {
                echo Notice::make('Settings saved.', 'success')->dismissible();
            }

            echo '<form method="post" action="options.php">';
            settings_fields('my_plugin_options');

            echo Metabox::make('API Configuration')->content(
                FormTable::make()
                    ->text('my_plugin_api_endpoint', 'API Endpoint', [
                        'value'       => get_option('my_plugin_api_endpoint', ''),
                        'placeholder' => 'https://api.example.com',
                    ])
                    ->password('my_plugin_api_key', 'API Key', [
                        'value' => get_option('my_plugin_api_key', ''),
                    ])
                    ->select('my_plugin_env', 'Environment', [
                        'production'  => 'Production',
                        'development' => 'Development',
                    ], ['value' => get_option('my_plugin_env', 'production')])
            );

            echo Button::make('Save Settings')->primary()->submit();
            echo '</form>';
        });
    });
});
```

### 3. Data Listing with Tabs and ListTable

```php
use FluentAdmin\Components\{Page, Tabs, ListTable};

add_menu_page('Logs', 'Logs', 'manage_options', 'my-logs', function () {
    Page::make('Plugin Logs')->render(function () {
        echo Tabs::make()
            ->tab('Recent', function () {
                echo ListTable::make()
                    ->columns(['timestamp' => 'Time', 'level' => 'Level', 'message' => 'Message'])
                    ->sortable(['timestamp', 'level'])
                    ->data(fn($args) => MyPlugin\Logs::query($args))
                    ->count(fn() => MyPlugin\Logs::count());
            })
            ->tab('Settings', function () {
                echo '<p>Log settings go here.</p>';
            });
    });
});
```

---

## Component Reference

| Component | Description |
|-----------|-------------|
| `Page` | Admin page wrapper (`<div class="wrap">`) |
| `Notice` | Info, success, warning, or error notice |
| `Button` | Link or submit button, all size variants |
| `ButtonGroup` | Grouped buttons (`<div class="button-group">`) |
| `Metabox` | Collapsible postbox panel |
| `MetaboxContainer` | Two-column metabox layout |
| `Tabs` | URL-based tab navigation |
| `FormTable` | Settings-style label/field rows |
| `DataTable` | Simple static data table |
| `ListTable` | Full sortable/paginated WP_List_Table |
| `Spinner` | Loading spinner |
| `Counter` | Count bubble (plugin update style) |
| `Dashicon` | WordPress dashicon |
| `Card` | Card-style panel |

### Form Fields

| Field | Description |
|-------|-------------|
| `TextField` | `<input type="text">` with size variants |
| `TextareaField` | `<textarea>` with configurable rows |
| `SelectField` | `<select>` dropdown |
| `CheckboxField` | Single checkbox with inline label |
| `RadioField` | Radio button group in fieldset |
| `PasswordField` | `<input type="password">` |
| `ColorField` | wp-color-picker integration |
| `MediaField` | Media library attachment chooser |

---

## Extensibility

### Custom Components

Extend `Component` and implement `html()`:

```php
<?php
namespace MyPlugin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

class StatusCard extends Component
{
    public function __construct(
        protected string $title,
        protected string $status,
        protected string $icon = 'info'
    ) {}

    protected function html(): string
    {
        return sprintf(
            '<div class="postbox"><div class="inside">
                <span class="dashicons dashicons-%s"></span>
                <h3>%s</h3>
                <p class="description">%s</p>
            </div></div>',
            Escape::attr($this->icon),
            Escape::html($this->title),
            Escape::html($this->status)
        );
    }
}

echo StatusCard::make('API Status', 'Connected', 'yes-alt');
```

### Custom Fields

Extend `Field` and implement `inputHtml()`:

```php
<?php
namespace MyPlugin\Fields;

use FluentAdmin\Fields\Field;
use FluentAdmin\Support\Escape;

class CodeEditorField extends Field
{
    protected function inputHtml(): string
    {
        wp_enqueue_code_editor(['type' => 'application/json']);
        return sprintf(
            '<textarea id="%s" name="%s" class="large-text code" rows="10">%s</textarea>',
            Escape::attr($this->getId()),
            Escape::attr($this->name),
            Escape::textarea((string) $this->value)
        );
    }
}
```

### WordPress Filters

Every component fires a filter before rendering:

```php
// Modify all notices
add_filter('fluent_admin_notice_render', function (string $html, array $config): string {
    return '<div class="my-wrapper">' . $html . '</div>';
}, 10, 2);

// All filter names follow the pattern: fluent_admin_{component}_render
// fluent_admin_page_render, fluent_admin_metabox_render,
// fluent_admin_formtable_render, fluent_admin_datatable_render, etc.
```

### PHP-Scoper

If multiple plugins might use wp-fluent-admin, prefix the namespace to avoid conflicts. See the [PHP-Scoper guide](docs/extensibility/php-scoper.md).

---

## Compatibility

- **PHP:** 8.0, 8.1, 8.2, 8.3
- **WordPress:** 6.0+
- **No JavaScript required** — pure PHP rendering
- **No CSS shipped** — relies on wp-admin's existing styles

---

## Documentation

The VitePress documentation site lives in `docs/`.

Local development:

```bash
cd docs
npm run docs:dev
```

Production build:

```bash
cd docs
npm run docs:build
```

GitHub Pages deployment is handled by [docs.yml](.github/workflows/docs.yml) on pushes to `main` that change `docs/**`.

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

---

## License

GPL-2.0-or-later — see [LICENSE](LICENSE).
