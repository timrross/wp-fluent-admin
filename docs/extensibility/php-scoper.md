# PHP-Scoper

Use PHP-Scoper to prefix `FluentAdmin\` when bundling your plugin so your dependency copy does not collide with other plugins.

## 1. Install PHP-Scoper

```bash
composer require --dev bamarni/composer-bin-plugin
composer bin scoper require --dev humbug/php-scoper:^0.18
```

This keeps PHP-Scoper isolated from the main project dependencies, so your test matrix can still install on the library's supported PHP versions while scoping remains available on a newer local PHP runtime.

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
vendor-bin/scoper/vendor/bin/php-scoper add-prefix \
  --config=scoper.inc.php \
  --output-dir=build/scoped
```

If you are working in this repository, `composer scope` will install the isolated tool and run the same command for you.

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
- PHP-Scoper itself requires a newer PHP version than the library runtime target. Run the scoping step on PHP 8.1+ even though the library itself supports PHP 8.0+.
