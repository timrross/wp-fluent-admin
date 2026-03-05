<?php

declare(strict_types=1);

namespace FluentAdmin\Support;

/**
 * Centralised escaping helpers.
 *
 * Delegates to WordPress escaping functions when available,
 * falling back to htmlspecialchars-based equivalents for unit tests.
 */
class Escape
{
    /**
     * Escape HTML text content.
     *
     * @param string $text
     * @return string
     */
    public static function html(string $text): string
    {
        if (function_exists('esc_html')) {
            return esc_html($text);
        }

        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Escape an HTML attribute value.
     *
     * @param string $text
     * @return string
     */
    public static function attr(string $text): string
    {
        if (function_exists('esc_attr')) {
            return esc_attr($text);
        }

        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Escape a URL for use in an href or src attribute.
     *
     * @param string $url
     * @return string
     */
    public static function url(string $url): string
    {
        if (function_exists('esc_url')) {
            return esc_url($url);
        }

        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Escape text for use inside a textarea.
     *
     * @param string $text
     * @return string
     */
    public static function textarea(string $text): string
    {
        if (function_exists('esc_textarea')) {
            return esc_textarea($text);
        }

        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
