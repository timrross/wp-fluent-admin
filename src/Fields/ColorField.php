<?php

declare(strict_types=1);

namespace FluentAdmin\Fields;

use FluentAdmin\Support\Escape;

/**
 * Renders a color picker field using WordPress's wp-color-picker.
 *
 * Output: <input type="text" class="wp-color-picker" id="{id}" name="{name}" value="{value}" />
 *
 * The caller must call ColorField::enqueue() during admin_enqueue_scripts
 * to load the wp-color-picker JS and CSS assets.
 */
class ColorField extends Field
{
    /**
     * Enqueue the wp-color-picker WordPress assets.
     * Call this in your admin_enqueue_scripts hook.
     *
     * @return void
     */
    public static function enqueue(): void
    {
        if (function_exists('wp_enqueue_script')) {
            wp_enqueue_script('wp-color-picker');
        }

        if (function_exists('wp_enqueue_style')) {
            wp_enqueue_style('wp-color-picker');
        }
    }

    /**
     * @return string
     */
    protected function inputHtml(): string
    {
        $id = Escape::attr($this->getId());
        $name = Escape::attr($this->name);
        $value = Escape::attr((string) $this->value);

        $extra = '';

        if ($this->required) {
            $extra .= ' required';
        }
        if ($this->disabled) {
            $extra .= ' disabled';
        }

        return sprintf(
            '<input type="text" class="wp-color-picker" id="%s" name="%s" value="%s"%s />',
            $id,
            $name,
            $value,
            $extra
        );
    }
}
