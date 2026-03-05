<?php

declare(strict_types=1);

namespace FluentAdmin\Fields;

use FluentAdmin\Support\Escape;

/**
 * Renders a textarea field.
 *
 * Output: <textarea id="{id}" name="{name}" class="large-text" rows="{rows}">{value}</textarea>
 */
class TextareaField extends Field
{
    protected int $rows = 5;

    /**
     * Set the number of visible text rows.
     *
     * @param int $rows
     * @return static
     */
    public function rows(int $rows): static
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * @return string
     */
    protected function inputHtml(): string
    {
        $id = Escape::attr($this->getId());
        $name = Escape::attr($this->name);
        $value = Escape::textarea((string) $this->value);

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
            '<textarea id="%s" name="%s" class="large-text" rows="%d"%s>%s</textarea>',
            $id,
            $name,
            $this->rows,
            $extra,
            $value
        );
    }
}
