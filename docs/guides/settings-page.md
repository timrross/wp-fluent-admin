# Building a Settings Page

Build a complete settings page with save handling, success notice, metabox layout, and fluent form fields.

## What we're building

- A top-level admin menu page
- A settings form saved to `wp_options`
- A success notice after save
- `Page` + `Metabox` + `FormTable` + `Button`

## Step 1: Register settings and menu page

```php
add_action('admin_init', function () {
    register_setting('fa_settings_group', 'fa_settings', [
        'type' => 'array',
        'sanitize_callback' => function ($input): array {
            return [
                'api_key' => sanitize_text_field((string) ($input['api_key'] ?? '')),
                'region' => sanitize_text_field((string) ($input['region'] ?? 'us')),
                'enabled' => !empty($input['enabled']) ? '1' : '0',
            ];
        },
        'default' => ['api_key' => '', 'region' => 'us', 'enabled' => '0'],
    ]);
});

add_action('admin_menu', function () {
    add_menu_page(
        'FA Settings',
        'FA Settings',
        'manage_options',
        'fa-settings',
        'fa_render_settings_page',
        'dashicons-admin-generic'
    );
});
```

## Step 2: Build the UI

```php
use FluentAdmin\Components\{Page, Notice, Metabox, FormTable, Button};

function fa_render_settings_page(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings = (array) get_option('fa_settings', []);
    $apiKey = (string) ($settings['api_key'] ?? '');
    $region = (string) ($settings['region'] ?? 'us');
    $enabled = (string) ($settings['enabled'] ?? '0') === '1';

    Page::make('Fluent Admin Settings')->icon('dashicons-admin-generic')->render(function () use ($apiKey, $region, $enabled) {
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') {
            echo Notice::make('Settings saved.', 'success')->dismissible();
        }

        echo '<form method="post" action="options.php">';
        settings_fields('fa_settings_group');

        echo Metabox::make('General Settings')->content(
            FormTable::make()
                ->text('fa_settings[api_key]', 'API Key', [
                    'value' => $apiKey,
                    'placeholder' => 'pk_live_...',
                    'description' => 'Used for authenticated API calls.',
                ])
                ->select('fa_settings[region]', 'Region', [
                    'us' => 'United States',
                    'eu' => 'Europe',
                    'apac' => 'Asia Pacific',
                ], [
                    'value' => $region,
                ])
                ->checkbox('fa_settings[enabled]', 'Enable integration', [
                    'checked' => $enabled,
                    'description' => 'Turn off to pause outbound requests.',
                ])
        );

        echo Button::make('Save Settings')->primary()->submit();
        echo '</form>';
    });
}
```

## Complete code

```php
<?php
/**
 * Plugin Name: FA Settings Page Guide
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use FluentAdmin\Components\{Page, Notice, Metabox, FormTable, Button};

add_action('admin_init', function () {
    register_setting('fa_settings_group', 'fa_settings', [
        'type' => 'array',
        'sanitize_callback' => function ($input): array {
            return [
                'api_key' => sanitize_text_field((string) ($input['api_key'] ?? '')),
                'region' => sanitize_text_field((string) ($input['region'] ?? 'us')),
                'enabled' => !empty($input['enabled']) ? '1' : '0',
            ];
        },
        'default' => ['api_key' => '', 'region' => 'us', 'enabled' => '0'],
    ]);
});

add_action('admin_menu', function () {
    add_menu_page(
        'FA Settings',
        'FA Settings',
        'manage_options',
        'fa-settings',
        'fa_render_settings_page',
        'dashicons-admin-generic'
    );
});

function fa_render_settings_page(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings = (array) get_option('fa_settings', []);
    $apiKey = (string) ($settings['api_key'] ?? '');
    $region = (string) ($settings['region'] ?? 'us');
    $enabled = (string) ($settings['enabled'] ?? '0') === '1';

    Page::make('Fluent Admin Settings')->icon('dashicons-admin-generic')->render(function () use ($apiKey, $region, $enabled) {
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') {
            echo Notice::make('Settings saved.', 'success')->dismissible();
        }

        echo '<form method="post" action="options.php">';
        settings_fields('fa_settings_group');

        echo Metabox::make('General Settings')->content(
            FormTable::make()
                ->text('fa_settings[api_key]', 'API Key', [
                    'value' => $apiKey,
                    'placeholder' => 'pk_live_...',
                ])
                ->select('fa_settings[region]', 'Region', [
                    'us' => 'United States',
                    'eu' => 'Europe',
                    'apac' => 'Asia Pacific',
                ], ['value' => $region])
                ->checkbox('fa_settings[enabled]', 'Enable integration', ['checked' => $enabled])
        );

        echo Button::make('Save Settings')->primary()->submit();
        echo '</form>';
    });
}
```
