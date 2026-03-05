<?php

declare(strict_types=1);

namespace FluentAdmin\Fields;

use FluentAdmin\Support\Escape;

/**
 * Renders a select dropdown field.
 *
 * Output: <select id="{id}" name="{name}">{options}</select>
 */
class SelectField extends Field
{
    /** @var array<string|int, string> */
    protected array $options = [];

    /**
     * @param string                    $name    The field name attribute.
     * @param string                    $label   The field label text.
     * @param array<string|int, string> $options Key => label option pairs.
     */
    public function __construct(string $name, string $label = '', array $options = [])
    {
        parent::__construct($name, $label);
        $this->options = $options;
    }

    /**
     * Set or replace the options array.
     *
     * @param array<string|int, string> $options
     * @return static
     */
    public function options(array $options): static
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return string
     */
    protected function inputHtml(): string
    {
        $id = Escape::attr($this->getId());
        $name = Escape::attr($this->name);

        $extra = '';
        if ($this->required) {
            $extra .= ' required';
        }
        if ($this->disabled) {
            $extra .= ' disabled';
        }

        $optionHtml = '';
        foreach ($this->options as $key => $label) {
            $val = Escape::attr((string) $key);
            $text = Escape::html((string) $label);
            $selected = ((string) $this->value === (string) $key) ? ' selected' : '';
            $optionHtml .= "<option value=\"{$val}\"{$selected}>{$text}</option>";
        }

        return "<select id=\"{$id}\" name=\"{$name}\"{$extra}>{$optionHtml}</select>";
    }
}
