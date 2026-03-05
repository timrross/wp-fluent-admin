<?php

declare(strict_types=1);

namespace FluentAdmin\Traits;

/**
 * Provides fluent HTML attribute management for components.
 */
trait HasAttributes
{
    /**
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * Set the element's id attribute.
     *
     * @param string $id
     * @return static
     */
    public function id(string $id): static
    {
        $this->attributes['id'] = $id;
        return $this;
    }

    /**
     * Add a CSS class to the element.
     *
     * @param string $class
     * @return static
     */
    public function addClass(string $class): static
    {
        if (!isset($this->attributes['class'])) {
            $this->attributes['class'] = [];
        }
        if (!is_array($this->attributes['class'])) {
            $this->attributes['class'] = explode(' ', (string) $this->attributes['class']);
        }
        $this->attributes['class'][] = $class;
        return $this;
    }

    /**
     * Remove a CSS class from the element.
     *
     * @param string $class
     * @return static
     */
    public function removeClass(string $class): static
    {
        if (!isset($this->attributes['class'])) {
            return $this;
        }
        if (!is_array($this->attributes['class'])) {
            $this->attributes['class'] = explode(' ', (string) $this->attributes['class']);
        }
        $this->attributes['class'] = array_values(
            array_filter($this->attributes['class'], fn($c) => $c !== $class)
        );
        return $this;
    }

    /**
     * Set an arbitrary HTML attribute.
     *
     * @param string $name
     * @param mixed  $value
     * @return static
     */
    public function attr(string $name, $value): static
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * Set a data-* attribute.
     *
     * @param string $name  The part after "data-"
     * @param mixed  $value
     * @return static
     */
    public function data(string $name, $value): static
    {
        $this->attributes["data-{$name}"] = $value;
        return $this;
    }
}
