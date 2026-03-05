<?php

declare(strict_types=1);

// phpcs:disable PSR1.Files.SideEffects

require_once __DIR__ . '/../vendor/autoload.php';

// Stub WordPress functions for unit tests when WP is not loaded.

if (!function_exists('esc_html')) {
    function esc_html(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_url')) {
    function esc_url(string $url): string
    {
        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_textarea')) {
    function esc_textarea(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field(string $str): string
    {
        return strip_tags(trim($str));
    }
}

if (!function_exists('sanitize_title')) {
    function sanitize_title(string $title): string
    {
        $title = strtolower($title);
        $title = preg_replace('/[^a-z0-9\-]/', '-', $title) ?? $title;
        $title = trim($title, '-');
        return $title;
    }
}

if (!function_exists('wp_kses')) {
    function wp_kses(string $string, array $allowed_html): string
    {
        return strip_tags($string);
    }
}

if (!function_exists('wp_kses_post')) {
    function wp_kses_post(string $data): string
    {
        return strip_tags($data, '<p><a><strong><em><ul><ol><li><br><hr>');
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters(string $tag, $value, ...$args)
    {
        if (!isset($GLOBALS['fluent_admin_applied_filters']) || !is_array($GLOBALS['fluent_admin_applied_filters'])) {
            $GLOBALS['fluent_admin_applied_filters'] = [];
        }

        $GLOBALS['fluent_admin_applied_filters'][] = $tag;
        return $value;
    }
}

if (!function_exists('add_query_arg')) {
    function add_query_arg($args, string $url = ''): string
    {
        if (is_array($args)) {
            $query = http_build_query($args);
        } else {
            $query = http_build_query([$args => $url]);
            $url = '';
        }

        if ($url === '') {
            $url = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
        }

        $separator = strpos($url, '?') !== false ? '&' : '?';
        return $url . $separator . $query;
    }
}

if (!function_exists('absint')) {
    function absint($maybeint): int
    {
        return abs((int) $maybeint);
    }
}

if (!function_exists('__')) {
    function __(string $text, string $domain = 'default'): string
    {
        return $text;
    }
}

if (!function_exists('esc_sql')) {
    function esc_sql(string $data): string
    {
        return addslashes($data);
    }
}

// phpcs:enable PSR1.Files.SideEffects
