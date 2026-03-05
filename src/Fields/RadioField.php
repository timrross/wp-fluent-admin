<?php

declare(strict_types=1);

namespace FluentAdmin\Fields;

use FluentAdmin\Support\Escape;

/**
 * Renders a group of radio buttons inside a fieldset.
 *
 * Output:
 * <fieldset>
 *   <label><input type="radio" name="{name}" value="{key}" [checked] /> {label}</label><br>
 *   ...
 * </fieldset>
 */
class RadioField extends Field
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
        $name = Escape::attr($this->name);

        $extra = '';
        if ($this->disabled) {
            $extra .= ' disabled';
        }

        $items = '';
        foreach ($this->options as $key => $label) {
            $val = Escape::attr((string) $key);
            $labelText = Escape::html((string) $label);
            $checked = ((string) $this->value === (string) $key) ? ' checked' : '';
            $id = Escape::attr($this->name . '_' . $key);

            $items .= sprintf(
                '<label><input type="radio" id="%s" name="%s" value="%s"%s%s /> %s</label><br>',
                $id,
                $name,
                $val,
                $checked,
                $extra,
                $labelText
            );
        }

        return "<fieldset>{$items}</fieldset>";
    }
}
