<?php

declare(strict_types=1);

namespace FluentAdmin\Fields;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Abstract base class for all form field components.
 *
 * html() returns the input + optional description paragraph.
 * FormTable wraps this in <tr><th><td>.
 */
abstract class Field extends Component
{
    protected string $name;
    protected string $label;

    /** @var mixed */
    protected $value = '';

    protected string $description = '';
    protected string $placeholder = '';
    protected bool $required = false;
    protected bool $disabled = false;

    /**
     * @param string $name  The field name attribute.
     * @param string $label The field label text.
     */
    public function __construct(string $name, string $label = '')
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * Set the field value.
     *
     * @param mixed $value
     * @return static
     */
    public function value($value): static
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Set the description text shown below the field.
     *
     * @param string $description
     * @return static
     */
    public function description(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the placeholder attribute.
     *
     * @param string $placeholder
     * @return static
     */
    public function placeholder(string $placeholder): static
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Mark the field as required.
     *
     * @return static
     */
    public function required(): static
    {
        $this->required = true;
        return $this;
    }

    /**
     * Mark the field as disabled.
     *
     * @return static
     */
    public function disabled(): static
    {
        $this->disabled = true;
        return $this;
    }

    /**
     * Get the field name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the field label.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get the field id (defaults to name).
     *
     * @return string
     */
    public function getId(): string
    {
        if (!empty($this->attributes['id'])) {
            return (string) $this->attributes['id'];
        }
        return $this->name;
    }

    /**
     * Each concrete field must implement this and return just the input element.
     *
     * @return string
     */
    abstract protected function inputHtml(): string;

    /**
     * Returns the input HTML + optional description paragraph.
     *
     * @return string
     */
    protected function html(): string
    {
        $output = $this->inputHtml();

        if ($this->description !== '') {
            $output .= '<p class="description">' . Escape::html($this->description) . '</p>';
        }

        return $output;
    }
}
