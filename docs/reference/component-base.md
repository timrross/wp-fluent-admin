# Component Base

`FluentAdmin\Component` is the abstract base for all components and fields.

## `Component` API

### `::make(...$args): static`

Factory constructor.

```php
$notice = \FluentAdmin\Components\Notice::make('Saved', 'success');
```

### `protected function resolveContent(callable|Component|string $content): string`

Resolves child content in this order:

1. `callable` via output buffering
2. `Component` via `->render()`
3. `string` escaped with `Escape::html()`

### `protected function resolveRawContent(string $html): string`

Returns trusted HTML without escaping.
This is an internal helper for custom subclasses; the public API does not currently expose a raw-HTML setter.

### `public function __call(string $name, array $args): static`

Generic fluent setter writing to internal `$config`.

```php
$component->foo('bar'); // $config['foo'] = 'bar'
```

### `abstract protected function html(): string`

Required implementation for renderable markup output.

## Traits used by `Component`

- `Renderable`: rendering lifecycle and filters
- `HasAttributes`: id/class/attribute helpers

Container components additionally use `HasChildren`.

See: [Traits Reference](/reference/traits).
