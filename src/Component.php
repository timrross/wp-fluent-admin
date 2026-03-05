<?php

declare(strict_types=1);

namespace FluentAdmin;

use FluentAdmin\Support\Escape;
use FluentAdmin\Traits\Renderable;
use FluentAdmin\Traits\HasAttributes;

/**
 * Abstract base class for all wp-fluent-admin components.
 */
abstract class Component
{
    use Renderable;
    use HasAttributes;

    /**
     * Generic config bag populated by __call().
     *
     * @var array<string, mixed>
     */
    protected array $config = [];

    /**
     * Static factory — enables Component::make(...$args).
     *
     * @param mixed ...$args
     * @return static
     */
    public static function make(...$args): static
    {
        return new static(...$args);
    }

    /**
     * Resolve a content value (callable, Component, or string) to a string.
     *
     * 1. callable  → captured via ob_start/ob_get_clean
     * 2. Component → render()
     * 3. string    → escaped via Escape::html()
     *
     * @param callable|Component|string $content
     * @return string
     */
    protected function resolveContent($content): string
    {
        if (is_callable($content)) {
            ob_start();
            $content();
            return (string) ob_get_clean();
        }

        if ($content instanceof self) {
            return $content->render();
        }

        return Escape::html((string) $content);
    }

    /**
     * Return raw HTML content without escaping.
     * Use only when the caller explicitly passes trusted HTML.
     *
     * @param string $html
     * @return string
     */
    protected function resolveRawContent(string $html): string
    {
        return $html;
    }

    /**
     * Generic fluent setter.
     * $component->someKey('value') sets $this->config['someKey'] = 'value'.
     *
     * @param string       $name
     * @param array<mixed> $args
     * @return static
     */
    public function __call(string $name, array $args): static
    {
        $this->config[$name] = $args[0] ?? true;
        return $this;
    }

    /**
     * Every component must implement this and return its rendered HTML.
     *
     * @return string
     */
    abstract protected function html(): string;
}
