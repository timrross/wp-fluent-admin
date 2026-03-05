<?php

declare(strict_types=1);

namespace FluentAdmin\Fields;

use FluentAdmin\Support\Escape;

/**
 * Renders a text input field.
 *
 * Output: <input type="text" id="{id}" name="{name}" value="{value}" class="{size-class}" />
 */
class TextField extends Field
{
    protected string $size = 'regular';

    /**
     * Set the input size class.
     *
     * @param string $size One of: small, regular, large
     * @return static
     */
    public function size(string $size): static
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    protected function inputHtml(): string
    {
        $sizeMap = [
            'small'   => 'small-text',
            'regular' => 'regular-text',
            'large'   => 'large-text',
        ];

        $class = $sizeMap[$this->size] ?? 'regular-text';

        $id = Escape::attr($this->getId());
        $name = Escape::attr($this->name);
        $value = Escape::attr((string) $this->value);
        $classAttr = Escape::attr($class);

        $extra = '';

        if ($this->placeholder !== '') {
            $extra .= ' placeholder="' . Escape::attr($this->placeholder) . '"';
        }
        if ($this->required) {
            $extra .= ' required';
        }
        if ($this->disabled) {
            $extra .= ' disabled';
        }

        return sprintf(
            '<input type="text" id="%s" name="%s" value="%s" class="%s"%s />',
            $id,
            $name,
            $value,
            $classAttr,
            $extra
        );
    }
}
