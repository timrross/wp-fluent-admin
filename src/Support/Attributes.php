<?php

declare(strict_types=1);

namespace FluentAdmin\Support;

/**
 * HTML attribute builder.
 *
 * Converts an associative array of attribute key/value pairs into
 * a rendered HTML attribute string. All values are escaped via Escape::attr().
 */
class Attributes
{
    /**
     * Build a rendered HTML attribute string from an array.
     *
     * Handles:
     * - Boolean attributes (true → attribute name only, false → omitted)
     * - class arrays (merged into a single space-separated string)
     * - data-* attributes (key "data" with array value, or "data-foo" directly)
     * - All other string/int values escaped via Escape::attr()
     *
     * @param array<string, mixed> $attributes
     * @return string
     */
    public static function build(array $attributes): string
    {
        $parts = [];

        foreach ($attributes as $key => $value) {
            $key = (string) $key;

            // Handle 'class' as an array
            if ($key === 'class' && is_array($value)) {
                $value = implode(' ', array_filter($value));
            }

            // Boolean attributes
            if ($value === true) {
                $parts[] = Escape::attr($key);
                continue;
            }

            // Skip false/null attributes
            if ($value === false || $value === null) {
                continue;
            }

            // Skip empty class attributes
            if ($key === 'class' && $value === '') {
                continue;
            }

            $parts[] = Escape::attr($key) . '="' . Escape::attr((string) $value) . '"';
        }

        if (empty($parts)) {
            return '';
        }

        return ' ' . implode(' ', $parts);
    }
}
