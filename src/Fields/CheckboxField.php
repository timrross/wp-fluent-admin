<?php

declare(strict_types=1);

namespace FluentAdmin\Fields;

use FluentAdmin\Support\Escape;

/**
 * Renders a checkbox field.
 *
 * Output: <label><input type="checkbox" id="{id}" name="{name}" value="1" [checked] /> {label}</label>
 */
class CheckboxField extends Field
{
    protected bool $isChecked = false;

    /**
     * Set the checked state.
     *
     * @param bool $checked
     * @return static
     */
    public function checked(bool $checked = true): static
    {
        $this->isChecked = $checked;
        return $this;
    }

    /**
     * @return string
     */
    protected function inputHtml(): string
    {
        $id = Escape::attr($this->getId());
        $name = Escape::attr($this->name);
        $labelText = Escape::html($this->label);

        $checked = $this->isChecked ? ' checked' : '';

        $extra = '';
        if ($this->disabled) {
            $extra .= ' disabled';
        }

        return sprintf(
            '<label><input type="checkbox" id="%s" name="%s" value="1"%s%s /> %s</label>',
            $id,
            $name,
            $checked,
            $extra,
            $labelText
        );
    }
}
