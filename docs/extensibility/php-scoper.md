# PHP-Scoper

Use PHP-Scoper to prefix `FluentAdmin\` when bundling your plugin so your dependency copy does not collide with other plugins.

## 1. Install PHP-Scoper

```bash
composer require --dev humbug/php-scoper
```

## 2. Add a scoper config

Create `scoper.inc.php` in your plugin root:

```php
<?php

declare(strict_types=1);

return [
    'prefix' => 'MyPluginScoped',
    'finders' => [],
    'exclude-namespaces' => [
        'Composer',
    ],
];
```

## 3. Build a prefixed copy

```bash
vendor/bin/php-scoper add-prefix \
  --config=scoper.inc.php \
  --output-dir=build/scoped
```

## 4. Update autoload usage

Point your plugin bootstrap at the scoped autoloader in your build artifact.

```php
require_once __DIR__ . '/build/scoped/vendor/autoload.php';
```

## 5. Verify namespace rewrite

Before:

```php
use FluentAdmin\Components\Page;
```

After scoping:

```php
use MyPluginScoped\FluentAdmin\Components\Page;
```

## Notes

- Scope during release/build, not during local source development.
- Keep tests running against unscoped source for simpler debugging.
