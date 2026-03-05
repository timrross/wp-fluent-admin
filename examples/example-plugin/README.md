# WP Fluent Admin — Example Plugin

Demonstrates all components in the wp-fluent-admin library in a working WordPress plugin.

## What it shows

- Page with dashicon title
- All four notice types (info, success, warning, error)
- Tabs with three panels
- Metabox with ButtonGroup
- Collapsed metabox
- Card with Dashicon, Spinner, and Counter
- FormTable with text, password, select, checkbox, radio, and textarea fields
- DataTable with striped rows

## Installation

### Option A — Use with the library checked out next to your WordPress install

1. Run `composer install` in the library root (`wp-fluent-admin/`):

```bash
cd wp-fluent-admin
composer install
```

2. Copy or symlink the example plugin into your WordPress plugins directory:

```bash
ln -s /path/to/wp-fluent-admin/examples/example-plugin /path/to/wordpress/wp-content/plugins/fluent-admin-example
```

The plugin's autoloader path (`../../vendor/autoload.php`) assumes this structure:
```
wp-content/
  plugins/
    fluent-admin-example/   ← symlink target is here
      example-plugin.php
    wp-fluent-admin/        ← library checked out here
      vendor/
        autoload.php
```

### Option B — Copy files

1. Copy the `wp-fluent-admin/` directory into `wp-content/plugins/`.
2. Run `composer install` inside `wp-content/plugins/wp-fluent-admin/`.
3. Copy `examples/example-plugin/` to `wp-content/plugins/fluent-admin-example/`.
4. Update the `require_once` path in `example-plugin.php` to point to the correct `vendor/autoload.php`.

## Activation

Activate the **WP Fluent Admin — Example Plugin** from the WordPress admin Plugins screen. A new "Fluent Admin" menu item will appear in the left sidebar.
