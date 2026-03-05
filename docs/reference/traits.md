# Traits Reference

## `Renderable`

Provides:

- `render(): string`
- `__toString(): string`
- `toHtml(): string`

### Behavior

- Calls concrete `html()` implementation.
- Applies filter: `fluent_admin_{shortclassname}_render`.
- Passes `string $html` and `array $config` to the filter callback.

## `HasAttributes`

Provides fluent attribute setters:

- `id(string $id): static`
- `addClass(string $class): static`
- `removeClass(string $class): static`
- `attr(string $name, mixed $value): static`
- `data(string $name, mixed $value): static`

Stores values in protected `$attributes` for later rendering.

## `HasChildren`

Provides container-child composition:

- `child(Component|string|callable $child): static`
- `children(array $children): static`
- `renderChildren(): string`

Callable children render via output buffering.
