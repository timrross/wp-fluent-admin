<?php

declare(strict_types=1);

namespace FluentAdmin\Traits;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Provides child component management for container components.
 */
trait HasChildren
{
    /**
     * @var array<int, Component|string|callable>
     */
    protected array $children = [];

    /**
     * Append a child component, string, or callable.
     *
     * @param Component|string|callable $child
     * @return static
     */
    public function child($child): static
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * Append multiple children at once.
     *
     * @param array<int, Component|string|callable> $children
     * @return static
     */
    public function children(array $children): static
    {
        foreach ($children as $child) {
            $this->child($child);
        }
        return $this;
    }

    /**
     * Render all children to a string.
     *
     * @return string
     */
    public function renderChildren(): string
    {
        $output = '';
        foreach ($this->children as $child) {
            if (is_callable($child)) {
                ob_start();
                $child();
                $output .= (string) ob_get_clean();
            } elseif ($child instanceof Component) {
                $output .= $child->render();
            } else {
                $output .= Escape::html((string) $child);
            }
        }
        return $output;
    }
}
