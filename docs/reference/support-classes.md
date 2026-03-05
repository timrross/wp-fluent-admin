# Support Classes

## `FluentAdmin\Support\Escape`

Static escaping helpers with WordPress fallback support.

### Methods

- `html(string $text): string`
- `attr(string $text): string`
- `url(string $url): string`
- `textarea(string $text): string`

Each method calls WordPress native escape functions if available and falls back to `htmlspecialchars()` in non-WP contexts (unit tests).

## `FluentAdmin\Support\Attributes`

Builds HTML attribute strings from associative arrays.

### Method

- `build(array $attributes): string`

### Supported behavior

- Boolean attributes (`true` => attribute name only, `false/null` => omitted)
- Class arrays merged to space-separated class strings
- String/int attribute values escaped with `Escape::attr()`
- `data` arrays expanded to `data-*` attributes (`['data' => ['state' => 'open']]` => `data-state="open"`)

## Current support classes

The current API includes `Escape` and `Attributes`.
