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

## `FluentAdmin\Support\Version`

`Version` is referenced in the project layout but is not present in the current `src/Support` implementation.

If added later, document its public constants/methods here.
