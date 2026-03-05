<?php

declare(strict_types=1);

namespace FluentAdmin\Traits;

/**
 * Provides render(), __toString(), and toHtml() for components.
 *
 * The using class must implement `html(): string`.
 */
trait Renderable
{
    /**
     * Return the rendered HTML string, passed through a WordPress filter.
     *
     * @return string
     */
    public function render(): string
    {
        $html = $this->html();

        $shortName = strtolower((new \ReflectionClass($this))->getShortName());

        /** @var array<string, mixed> $config */
        $config = property_exists($this, 'config') ? $this->config : [];

        if (function_exists('apply_filters')) {
            return (string) apply_filters("fluent_admin_{$shortName}_render", $html, $config);
        }

        return $html;
    }

    /**
     * Allow echo $component to work.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Alias of render().
     *
     * @return string
     */
    public function toHtml(): string
    {
        return $this->render();
    }
}
