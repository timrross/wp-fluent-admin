<?php

declare(strict_types=1);

namespace FluentAdmin\Fields;

use FluentAdmin\Support\Escape;

/**
 * Renders a password input field.
 *
 * Output: <input type="password" id="{id}" name="{name}" value="{value}" class="regular-text" />
 */
class PasswordField extends Field
{
    /**
     * @return string
     */
    protected function inputHtml(): string
    {
        $id = Escape::attr($this->getId());
        $name = Escape::attr($this->name);
        $value = Escape::attr((string) $this->value);

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
            '<input type="password" id="%s" name="%s" value="%s" class="regular-text"%s />',
            $id,
            $name,
            $value,
            $extra
        );
    }
}
