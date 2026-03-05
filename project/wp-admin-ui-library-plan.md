# WP Admin UI — A Fluent PHP Component Library for WordPress Admin Pages

## The Problem

Building admin pages in WordPress means hand-coding HTML boilerplate with magic CSS classes. There's no PHP abstraction layer. You end up writing `<div class="wrap">`, `<table class="wp-list-table widefat striped">`, and `<div class="notice notice-success">` over and over. The markup is fragile, poorly documented, and inconsistent across WordPress versions.

## The Vision

A zero-dependency PHP library that any plugin developer can drop in via Composer. It provides a fluent, chainable API that outputs native WordPress admin markup — no custom CSS, no JavaScript framework, no opinions about data or routing. Just components that render the same HTML WordPress core uses.

```php
use AdminUI\Page;
use AdminUI\Components\{Notice, Table, Metabox, Tabs, Button};

Page::create('My Plugin Settings')
    ->notice('Settings saved.', 'success')
    ->tabs([
        'General' => function ($tab) {
            $tab->metabox('API Configuration', function ($box) {
                $box->formTable([
                    'api_key'    => ['label' => 'API Key', 'type' => 'text'],
                    'api_secret' => ['label' => 'API Secret', 'type' => 'password'],
                    'region'     => ['label' => 'Region', 'type' => 'select', 'options' => [
                        'us' => 'United States',
                        'eu' => 'Europe',
                    ]],
                ]);
            });
        },
        'Logs' => function ($tab) {
            $tab->listTable('log_entries')
                ->columns(['date' => 'Date', 'level' => 'Level', 'message' => 'Message'])
                ->sortable(['date', 'level'])
                ->bulkActions(['delete' => 'Delete'])
                ->perPage(25)
                ->dataCallback(function ($args) {
                    return MyPlugin::get_logs($args);
                });
        },
    ])
    ->render();
```

That replaces what would otherwise be 150+ lines of procedural HTML and PHP.

---

## Component Inventory

Based on the elements documented at wpadmin.bracketspace.com and common admin patterns:

### Tier 1 — Core Layout (build first)

| Component | What it wraps | WordPress markup |
|-----------|--------------|-----------------|
| **Page** | Full admin page wrapper | `<div class="wrap"><h1>...</h1>...</div>` |
| **Notice** | Admin notices (success, error, warning, info) | `<div class="notice notice-{type}">` |
| **Button** | All button variants | `<a class="button button-primary button-hero">` |
| **ButtonGroup** | Grouped buttons | `<div class="button-group">` |
| **Metabox** | Collapsible panels | `<div class="postbox"><div class="inside">` |
| **MetaboxContainer** | Side-by-side metabox layout | `<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">` |

### Tier 2 — Data Display

| Component | What it wraps | WordPress markup |
|-----------|--------------|-----------------|
| **FormTable** | Settings-style label/field rows | `<table class="form-table"><tr><th>...<td>...` |
| **ListTable** | Sortable, paginated data tables | Full `WP_List_Table` subclass |
| **DataTable** | Simple static tables | `<table class="wp-list-table widefat striped">` |
| **Counter** | Bubble counts (like plugin updates) | `<span class="count">` / `<span class="update-plugins">` |

### Tier 3 — Navigation & Feedback

| Component | What it wraps | WordPress markup |
|-----------|--------------|-----------------|
| **Tabs** | Tab navigation with content panels | `<h2 class="nav-tab-wrapper"><a class="nav-tab">` |
| **Spinner** | Loading indicators | `<span class="spinner is-active">` |
| **ScreenOptions** | Per-page screen options | Hooks into `add_screen_option()` |

### Tier 4 — Form Fields (for use inside FormTable)

| Component | What it wraps |
|-----------|--------------|
| **TextField** | `<input type="text" class="regular-text">` |
| **TextareaField** | `<textarea class="large-text">` |
| **SelectField** | `<select>` |
| **CheckboxField** | `<input type="checkbox">` + label |
| **RadioField** | Radio button groups |
| **PasswordField** | `<input type="password">` |
| **ColorField** | Color picker (with `wp-color-picker`) |
| **MediaField** | Media library chooser |
| **DescriptionField** | `<p class="description">` helper text |

### Tier 5 — Utility

| Component | What it wraps |
|-----------|--------------|
| **Dashicon** | `<span class="dashicons dashicons-{name}">` |
| **ProgressBar** | `<div class="health-check-progressbar">` |
| **Card** | The newer card-style panels |
| **HeaderBar** | Top-of-page bar pattern (like WooCommerce uses) |

---

## Architecture

### Design Principles

1. **Output-only** — Components render HTML strings. No data layer, no ORM, no routing. This is strictly a view library.
2. **Fluent API** — Every setter returns `$this`. Components can be built via chaining or array config.
3. **Composable** — Components accept other components as children. A Page contains Tabs which contain Metaboxes which contain FormTables.
4. **Escape by default** — All output is escaped through `esc_html()`, `esc_attr()`, `esc_url()`. Raw HTML must be explicitly opted into.
5. **No custom CSS or JS** — The library ships zero stylesheets. It relies entirely on WordPress core's admin CSS. If WordPress changes a class name, the library is the single place to update.
6. **Filterable** — Key rendering methods fire WordPress filters so other plugins can modify output.

### File Structure

```
wp-admin-ui/
├── composer.json
├── src/
│   ├── AdminUI.php                  # Factory / static entry point
│   ├── Component.php                # Abstract base class
│   ├── Traits/
│   │   ├── Renderable.php           # render(), toHtml(), __toString()
│   │   ├── HasAttributes.php        # HTML attributes, classes, IDs
│   │   ├── HasChildren.php          # Child component management
│   │   └── Fluent.php               # Generic fluent setter generation
│   ├── Components/
│   │   ├── Page.php
│   │   ├── Notice.php
│   │   ├── Button.php
│   │   ├── ButtonGroup.php
│   │   ├── Metabox.php
│   │   ├── MetaboxContainer.php
│   │   ├── Tabs.php
│   │   ├── Tab.php
│   │   ├── FormTable.php
│   │   ├── ListTable.php
│   │   ├── DataTable.php
│   │   ├── Spinner.php
│   │   ├── Counter.php
│   │   ├── Dashicon.php
│   │   └── Card.php
│   ├── Fields/
│   │   ├── Field.php                # Abstract base field
│   │   ├── TextField.php
│   │   ├── TextareaField.php
│   │   ├── SelectField.php
│   │   ├── CheckboxField.php
│   │   ├── RadioField.php
│   │   ├── PasswordField.php
│   │   ├── ColorField.php
│   │   └── MediaField.php
│   └── Support/
│       ├── Escape.php               # Centralised escaping helpers
│       ├── Attributes.php           # HTML attribute builder
│       └── Version.php              # WP version compat checks
├── tests/
│   ├── Unit/
│   │   ├── Components/
│   │   └── Fields/
│   └── Integration/                 # Tests that need WP loaded
└── README.md
```

### The Base Component Class

```php
<?php
namespace AdminUI;

abstract class Component
{
    use Traits\Renderable;
    use Traits\HasAttributes;

    protected array $config = [];

    /**
     * Static factory — enables AdminUI\Components\Notice::make()
     */
    public static function make(...$args): static
    {
        return new static(...$args);
    }

    /**
     * Every component must implement this.
     * Returns the raw HTML string.
     */
    abstract protected function html(): string;

    /**
     * Generic fluent setter.
     * $component->id('my-box') sets $this->config['id'] = 'my-box'
     */
    public function __call(string $name, array $args): static
    {
        $this->config[$name] = $args[0] ?? true;
        return $this;
    }

    /**
     * Allows echo $component directly.
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Renders with a WordPress filter applied.
     */
    public function render(): string
    {
        $html = $this->html();
        $class = static::class;
        $shortName = strtolower(class_basename($class));

        return apply_filters("adminui_{$shortName}_render", $html, $this->config);
    }
}
```

### Example Component — Notice

```php
<?php
namespace AdminUI\Components;

use AdminUI\Component;

class Notice extends Component
{
    protected string $message;
    protected string $type;
    protected bool $dismissible;

    public function __construct(string $message, string $type = 'info', bool $dismissible = false)
    {
        $this->message = $message;
        $this->type = $type;
        $this->dismissible = $dismissible;
    }

    public function dismissible(bool $value = true): static
    {
        $this->dismissible = $value;
        return $this;
    }

    public function alt(bool $value = true): static
    {
        $this->config['alt'] = $value;
        return $this;
    }

    protected function html(): string
    {
        $classes = ['notice'];

        if ($this->type !== 'default') {
            $classes[] = "notice-{$this->type}";
        }
        if ($this->dismissible) {
            $classes[] = 'is-dismissible';
        }
        if (!empty($this->config['alt'])) {
            $classes[] = 'notice-alt';
        }

        $class = esc_attr(implode(' ', $classes));
        $message = esc_html($this->message);

        return "<div class=\"{$class}\"><p>{$message}</p></div>";
    }
}
```

Usage:

```php
echo Notice::make('Settings saved successfully.', 'success')->dismissible();
echo Notice::make('API key is missing.', 'error');
echo Notice::make('New version available.', 'warning')->alt()->dismissible();
```

### Example Component — Tabs

```php
<?php
namespace AdminUI\Components;

use AdminUI\Component;

class Tabs extends Component
{
    protected array $tabs = [];
    protected ?string $active = null;

    public function tab(string $label, callable|string $content): static
    {
        $this->tabs[$label] = $content;
        if ($this->active === null) {
            $this->active = $label;
        }
        return $this;
    }

    public function active(string $label): static
    {
        $this->active = $label;
        return $this;
    }

    protected function html(): string
    {
        // Determine active tab from $_GET or default
        $current = sanitize_text_field($_GET['tab'] ?? '');
        $slugs = [];
        foreach (array_keys($this->tabs) as $label) {
            $slugs[$label] = sanitize_title($label);
        }

        if (!in_array($current, $slugs, true)) {
            $current = $slugs[$this->active] ?? reset($slugs);
        }

        // Render tab navigation
        $nav = '<h2 class="nav-tab-wrapper">';
        foreach ($this->tabs as $label => $content) {
            $slug = $slugs[$label];
            $activeClass = ($slug === $current) ? ' nav-tab-active' : '';
            $url = add_query_arg('tab', $slug);
            $nav .= sprintf(
                '<a href="%s" class="nav-tab%s">%s</a>',
                esc_url($url),
                esc_attr($activeClass),
                esc_html($label)
            );
        }
        $nav .= '</h2>';

        // Render active tab content
        $activeLabel = array_search($current, $slugs, true);
        $content = $this->tabs[$activeLabel] ?? '';
        $body = '<div class="tab-content" style="padding-top: 12px;">';
        if (is_callable($content)) {
            ob_start();
            $content();
            $body .= ob_get_clean();
        } else {
            $body .= $content;
        }
        $body .= '</div>';

        return $nav . $body;
    }
}
```

Usage:

```php
Tabs::make()
    ->tab('General', function () {
        echo Metabox::make('Settings', function () {
            echo FormTable::make()
                ->text('site_name', 'Site Name')
                ->select('region', 'Region', ['us' => 'US', 'eu' => 'EU']);
        });
    })
    ->tab('Advanced', function () {
        echo Notice::make('Careful with these settings.', 'warning');
        echo FormTable::make()
            ->checkbox('debug_mode', 'Enable Debug Mode');
    })
    ->render();
```

### Example Component — ListTable

This is the most complex component because it wraps `WP_List_Table`. The approach is to generate a concrete subclass at runtime:

```php
<?php
namespace AdminUI\Components;

class ListTable extends \AdminUI\Component
{
    protected array $columns = [];
    protected array $sortableColumns = [];
    protected array $bulkActions = [];
    protected int $perPage = 20;
    protected $dataCallback;
    protected $countCallback;

    public function columns(array $columns): static
    {
        $this->columns = $columns;
        return $this;
    }

    public function sortable(array $columns): static
    {
        $this->sortableColumns = $columns;
        return $this;
    }

    public function bulkActions(array $actions): static
    {
        $this->bulkActions = $actions;
        return $this;
    }

    public function perPage(int $count): static
    {
        $this->perPage = $count;
        return $this;
    }

    public function data(callable $callback): static
    {
        $this->dataCallback = $callback;
        return $this;
    }

    public function count(callable $callback): static
    {
        $this->countCallback = $callback;
        return $this;
    }

    protected function html(): string
    {
        $table = new DynamicListTable([
            'columns'   => $this->columns,
            'sortable'  => $this->sortableColumns,
            'bulk'      => $this->bulkActions,
            'per_page'  => $this->perPage,
            'data_cb'   => $this->dataCallback,
            'count_cb'  => $this->countCallback,
        ]);

        $table->prepare_items();

        ob_start();
        $table->display();
        return ob_get_clean();
    }
}

/**
 * Internal class — concrete WP_List_Table driven by config array.
 */
class DynamicListTable extends \WP_List_Table
{
    protected array $conf;

    public function __construct(array $conf)
    {
        $this->conf = $conf;
        parent::__construct([
            'singular' => 'item',
            'plural'   => 'items',
            'ajax'     => false,
        ]);
    }

    public function get_columns(): array
    {
        $cols = [];
        if (!empty($this->conf['bulk'])) {
            $cols['cb'] = '<input type="checkbox" />';
        }
        foreach ($this->conf['columns'] as $key => $label) {
            $cols[$key] = $label;
        }
        return $cols;
    }

    public function get_sortable_columns(): array
    {
        $sortable = [];
        foreach ($this->conf['sortable'] as $col) {
            $sortable[$col] = [$col, false];
        }
        return $sortable;
    }

    public function get_bulk_actions(): array
    {
        return $this->conf['bulk'];
    }

    public function column_default($item, $column_name)
    {
        return esc_html($item[$column_name] ?? '');
    }

    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="bulk-ids[]" value="%s" />', esc_attr($item['id'] ?? ''));
    }

    public function prepare_items(): void
    {
        $this->_column_headers = [
            $this->get_columns(),
            [],
            $this->get_sortable_columns(),
        ];

        $per_page = $this->conf['per_page'];
        $current_page = $this->get_pagenum();

        $args = [
            'per_page' => $per_page,
            'page'     => $current_page,
            'orderby'  => sanitize_text_field($_GET['orderby'] ?? ''),
            'order'    => sanitize_text_field($_GET['order'] ?? 'asc'),
        ];

        $this->items = call_user_func($this->conf['data_cb'], $args);
        $total = call_user_func($this->conf['count_cb']);

        $this->set_pagination_args([
            'total_items' => $total,
            'per_page'    => $per_page,
        ]);
    }
}
```

Usage:

```php
ListTable::make()
    ->columns([
        'title'  => 'Title',
        'author' => 'Author',
        'date'   => 'Date',
    ])
    ->sortable(['title', 'date'])
    ->bulkActions(['delete' => 'Delete', 'export' => 'Export'])
    ->perPage(25)
    ->data(function ($args) {
        return MyPlugin::get_entries($args['per_page'], $args['page'], $args['orderby'], $args['order']);
    })
    ->count(function () {
        return MyPlugin::count_entries();
    })
    ->render();
```

### Example — Full Admin Page

Putting it all together, here's what a complete settings page looks like:

```php
<?php
// In your plugin's admin page callback:

use AdminUI\Components\{Page, Notice, Tabs, Metabox, FormTable, ListTable, Button, ButtonGroup};

Page::make('My Plugin')
    ->screenOption('per_page', ['label' => 'Items', 'default' => 20])
    ->render(function ($page) {

        if (isset($_GET['settings-updated'])) {
            echo Notice::make('Settings saved.', 'success')->dismissible();
        }

        echo Tabs::make()
            ->tab('Dashboard', function () {
                echo Metabox::make('Overview')
                    ->content('<p>Welcome to the plugin dashboard.</p>');

                echo Metabox::make('Quick Actions')
                    ->content(
                        ButtonGroup::make()
                            ->add(Button::make('Run Sync', '#')->primary())
                            ->add(Button::make('Clear Cache', '#'))
                            ->add(Button::make('Export', '#'))
                    );
            })
            ->tab('Settings', function () {
                echo '<form method="post" action="options.php">';
                settings_fields('my_plugin_options');

                echo Metabox::make('Connection')
                    ->content(
                        FormTable::make()
                            ->text('api_endpoint', 'API Endpoint', ['placeholder' => 'https://...'])
                            ->password('api_key', 'API Key')
                            ->select('environment', 'Environment', [
                                'production'  => 'Production',
                                'staging'     => 'Staging',
                                'development' => 'Development',
                            ])
                            ->checkbox('verify_ssl', 'Verify SSL', ['description' => 'Disable for local dev only.'])
                    );

                echo Button::make('Save Changes')->primary()->submit();
                echo '</form>';
            })
            ->tab('Logs', function () {
                echo ListTable::make()
                    ->columns([
                        'timestamp' => 'Time',
                        'level'     => 'Level',
                        'message'   => 'Message',
                        'context'   => 'Context',
                    ])
                    ->sortable(['timestamp', 'level'])
                    ->bulkActions(['delete' => 'Delete Selected'])
                    ->perPage(50)
                    ->data(fn($args) => MyPlugin\Logs::query($args))
                    ->count(fn() => MyPlugin\Logs::count());
            });
    });
```

Compare that to the 300+ lines of raw HTML/PHP you'd write without it.

---

## Extensibility

### WordPress Filters on Every Component

Every component fires a filter before rendering:

```php
// Filter any notice before output
add_filter('adminui_notice_render', function (string $html, array $config) {
    // Wrap all notices in a custom container
    return '<div class="my-plugin-notice-wrapper">' . $html . '</div>';
}, 10, 2);

// Filter a specific metabox
add_filter('adminui_metabox_render', function (string $html, array $config) {
    if (($config['id'] ?? '') === 'my-special-box') {
        // Inject content
    }
    return $html;
}, 10, 2);
```

### Custom Components

Developers can create their own components by extending the base class:

```php
<?php
namespace MyPlugin\Components;

use AdminUI\Component;

class StatusCard extends Component
{
    public function __construct(
        protected string $title,
        protected string $value,
        protected string $icon = 'dashicons-info'
    ) {}

    protected function html(): string
    {
        return sprintf(
            '<div class="postbox"><div class="inside">
                <span class="dashicons %s"></span>
                <h3>%s</h3>
                <p class="description">%s</p>
            </div></div>',
            esc_attr($this->icon),
            esc_html($this->title),
            esc_html($this->value)
        );
    }
}

// Usage
echo StatusCard::make('API Status', 'Connected', 'dashicons-yes-alt');
```

### Custom Field Types

```php
<?php
namespace MyPlugin\Fields;

use AdminUI\Fields\Field;

class CodeEditorField extends Field
{
    protected function inputHtml(): string
    {
        wp_enqueue_code_editor(['type' => 'application/json']);
        return sprintf(
            '<textarea id="%s" name="%s" class="large-text code" rows="10">%s</textarea>',
            esc_attr($this->id()),
            esc_attr($this->name()),
            esc_textarea($this->value())
        );
    }
}
```

---

## Distribution & Inclusion

### For Plugin Developers (Composer)

```json
{
    "require": {
        "your-vendor/wp-admin-ui": "^1.0"
    }
}
```

The library uses PHP namespaces and PSR-4 autoloading. No global functions pollute the namespace.

### Handling Multiple Plugins Using the Same Library

This is the classic WordPress "dependency hell" problem. Two approaches:

**Option A — Mozart / PHP-Scoper (recommended)**

Each plugin vendor prefixes the namespace at build time:

```php
// Plugin A sees: MyPluginA\Vendor\AdminUI\Components\Notice
// Plugin B sees: MyPluginB\Vendor\AdminUI\Components\Notice
```

This is what libraries like Strauss or PHP-Scoper do. Each plugin ships its own isolated copy. No conflicts.

**Option B — Shared singleton with version check**

The library registers itself globally and only loads the highest version:

```php
// In the library bootstrap:
if (!defined('ADMINUI_VERSION') || version_compare(ADMINUI_VERSION, '1.2.0', '<')) {
    define('ADMINUI_VERSION', '1.2.0');
    // Register autoloader
}
```

Option A is safer. Option B is lighter but risks subtle breakage if a newer version drops something.

---

## Effort Estimate

### Phase 1 — Foundation + Tier 1 Components
**~2–3 weeks for one experienced developer**

- Abstract base class, traits, escaping utilities
- Page, Notice, Button, ButtonGroup, Metabox, MetaboxContainer
- Composer package setup, PSR-4 autoloading
- Unit tests for all components (PHPUnit)
- Basic README with examples

### Phase 2 — Data Components
**~2–3 weeks**

- FormTable with all field types (text, select, checkbox, radio, textarea, password)
- ListTable wrapper (the hardest single component — WP_List_Table is gnarly)
- DataTable (simple static table)
- Counter, Spinner
- Integration tests requiring WordPress loaded (wp-env or similar)

### Phase 3 — Navigation + Polish
**~1–2 weeks**

- Tabs component with URL-based state
- Screen options integration
- Dashicon helper
- Card component
- Full documentation site or detailed README

### Phase 4 — Advanced Fields + Extensibility
**~1–2 weeks**

- ColorField (wp-color-picker integration)
- MediaField (media library integration)
- Filter/hook documentation
- Custom component guide
- Example plugin demonstrating all components

### Phase 5 — Packaging + Community
**~1 week**

- PHP-Scoper / Mozart config for conflict-free inclusion
- Packagist publication
- GitHub Actions CI (tests on PHP 7.4, 8.0, 8.1, 8.2, 8.3 × WP 6.0+)
- Contribution guide

### Total: ~8–11 weeks for a solid v1.0

That's for a single developer working consistently. A pair could compress it to 5–6 weeks. The ListTable wrapper is the single most time-consuming piece — probably 3–4 days on its own due to the complexity of WP_List_Table's internals (pagination, nonces, bulk actions, screen options, column toggling).

### What You Could Ship as a Useful MVP in 2 Weeks

Page, Notice, Button, Metabox, FormTable (text/select/checkbox only), and Tabs. That covers 80% of what plugin developers actually build. The ListTable wrapper is valuable but you could defer it to v1.1 and still have something people would use immediately.

---

## Compatibility Target

- **PHP**: 7.4+ (matches WordPress minimum)
- **WordPress**: 6.0+ (admin markup has been stable since ~5.0)
- **No JavaScript dependencies** — pure PHP rendering
- **No CSS shipped** — relies on wp-admin's existing styles

## Naming Options

Some available names to consider: `wp-admin-ui`, `adminkit`, `wp-fluent-admin`, `adminblocks`, `wp-panel`.

The name should be clear about what it does (WordPress admin UI) and not conflict with existing packages on Packagist.
