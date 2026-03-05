<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

// php-scoper currently triggers deprecation notices on newer PHP versions.
// Suppress deprecations for deterministic builds.
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

$prefix = getenv('FLUENT_ADMIN_SCOPER_PREFIX');
if (!is_string($prefix) || trim($prefix) === '') {
    // Default result for classes under FluentAdmin\*: Isolated\FluentAdmin\*
    $prefix = 'Isolated';
}

$prefix = trim($prefix, '\\');

return [
    // Default effective prefix for this library: Isolated\FluentAdmin
    'prefix' => $prefix,

    // php-scoper script also sets --output-dir=build/
    'output-dir' => 'build',

    'finders' => [
        Finder::create()
            ->files()
            ->in([
                __DIR__ . '/src',
            ]),
    ],

    'exclude-namespaces' => [
        'Composer',
    ],

    // Keep WordPress globals unprefixed.
    'expose-classes' => [
        'WP_List_Table',
    ],
    'expose-functions' => [
        '__',
        'absint',
        'add_query_arg',
        'admin_url',
        'apply_filters',
        'esc_attr',
        'esc_html',
        'esc_sql',
        'esc_textarea',
        'esc_url',
        'sanitize_text_field',
        'sanitize_title',
        'wp_enqueue_media',
        'wp_enqueue_script',
        'wp_enqueue_style',
        'wp_get_attachment_image_url',
        'wp_kses',
        'wp_kses_post',
        'wp_nonce_field',
        'wp_nonce_url',
        'wp_safe_redirect',
        'wp_verify_nonce',
    ],
];
