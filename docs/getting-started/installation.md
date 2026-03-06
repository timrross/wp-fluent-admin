# Installation

## Requirements

- PHP 8.0 or higher
- WordPress 6.0 or higher
- Composer

## Install via Composer

```bash
composer require wp-fluent-admin/wp-fluent-admin
```

## Load the autoloader

In your plugin's main file, require Composer's autoloader:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

No service provider or bootstrapping class is required.

## Avoiding conflicts with other plugins

If multiple plugins in the same WordPress install could ship `wp-fluent-admin`,
prefix the namespace with PHP-Scoper in your build pipeline.
See the [PHP-Scoper guide](/extensibility/php-scoper).
