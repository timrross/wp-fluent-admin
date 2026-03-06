# Building a Multi-Tab Settings Page

Build a settings page split into multiple sections using URL-persisted tabs.

## What we're building

- `Page` with a save notice
- `Tabs` with three sections
- A shared save handler for all tabs
- Different `FormTable` layouts per tab

## Step 1: Register the admin page

```php
add_action('admin_menu', function () {
    add_menu_page(
        'FA Multi-Tab Settings',
        'FA Settings',
        'manage_options',
        'fa-multi-settings',
        'fa_render_multi_settings',
        'dashicons-admin-settings'
    );
});
```

## Step 2: Shared save handler

```php
function fa_handle_multi_settings_save(): void
{
    if ('POST' !== ($_SERVER['REQUEST_METHOD'] ?? '')) {
        return;
    }

    if (!isset($_POST['fa_multi_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['fa_multi_nonce']));

    if (!wp_verify_nonce($nonce, 'fa_multi_save')) {
        return;
    }

    $generalPost = isset($_POST['fa_general']) && is_array($_POST['fa_general'])
        ? wp_unslash($_POST['fa_general'])
        : [];
    $apiPost = isset($_POST['fa_api']) && is_array($_POST['fa_api'])
        ? wp_unslash($_POST['fa_api'])
        : [];
    $notificationsPost = isset($_POST['fa_notifications']) && is_array($_POST['fa_notifications'])
        ? wp_unslash($_POST['fa_notifications'])
        : [];
    $currentTab = isset($_GET['tab']) ? sanitize_title(wp_unslash($_GET['tab'])) : '';

    update_option('fa_general', [
        'site_label' => sanitize_text_field((string) ($generalPost['site_label'] ?? '')),
        'timezone' => sanitize_text_field((string) ($generalPost['timezone'] ?? 'UTC')),
    ]);

    update_option('fa_api', [
        'api_key' => sanitize_text_field((string) ($apiPost['api_key'] ?? '')),
        'mode' => sanitize_text_field((string) ($apiPost['mode'] ?? 'test')),
    ]);

    update_option('fa_notifications', [
        'email_enabled' => !empty($notificationsPost['email_enabled']) ? '1' : '0',
    ]);

    $redirectUrl = admin_url('admin.php?page=fa-multi-settings&settings-updated=true');

    if ('' !== $currentTab) {
        $redirectUrl = add_query_arg('tab', $currentTab, $redirectUrl);
    }

    wp_safe_redirect($redirectUrl);
    exit;
}
```

## Step 3: Build tab content

```php
use FluentAdmin\Components\{Page, Notice, Tabs, FormTable, Button};

function fa_render_multi_settings(): void
{
    fa_handle_multi_settings_save();

    $general = (array) get_option('fa_general', ['site_label' => '', 'timezone' => 'UTC']);
    $api = (array) get_option('fa_api', ['api_key' => '', 'mode' => 'test']);
    $notifications = (array) get_option('fa_notifications', ['email_enabled' => '0']);
    $updated = isset($_GET['settings-updated'])
        && 'true' === sanitize_text_field(wp_unslash($_GET['settings-updated']));

    Page::make('Multi-Tab Settings')->render(function () use ($general, $api, $notifications, $updated) {
        if ($updated) {
            echo Notice::make('Settings saved.', 'success')->dismissible();
        }

        echo '<form method="post">';
        wp_nonce_field('fa_multi_save', 'fa_multi_nonce');

        echo Tabs::make()
            ->tab('General', FormTable::make()
                ->text('fa_general[site_label]', 'Site Label', ['value' => (string) $general['site_label']])
                ->select('fa_general[timezone]', 'Timezone', ['UTC' => 'UTC', 'EST' => 'EST', 'PST' => 'PST'], ['value' => (string) $general['timezone']])
            )
            ->tab('API', FormTable::make()
                ->text('fa_api[api_key]', 'API Key', ['value' => (string) $api['api_key']])
                ->radio('fa_api[mode]', 'Mode', ['live' => 'Live', 'test' => 'Test'], ['value' => (string) $api['mode']])
            )
            ->tab('Notifications', FormTable::make()
                ->checkbox('fa_notifications[email_enabled]', 'Enable Email Alerts', ['checked' => (string) $notifications['email_enabled'] === '1'])
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
 * Plugin Name: FA Multi-Tab Settings Guide
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use FluentAdmin\Components\{Page, Notice, Tabs, FormTable, Button};

add_action('admin_menu', function () {
    add_menu_page('FA Multi-Tab Settings', 'FA Settings', 'manage_options', 'fa-multi-settings', 'fa_render_multi_settings', 'dashicons-admin-settings');
});

function fa_handle_multi_settings_save(): void
{
    if ('POST' !== ($_SERVER['REQUEST_METHOD'] ?? '')) {
        return;
    }

    if (!isset($_POST['fa_multi_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['fa_multi_nonce']));

    if (!wp_verify_nonce($nonce, 'fa_multi_save')) {
        return;
    }

    $generalPost = isset($_POST['fa_general']) && is_array($_POST['fa_general'])
        ? wp_unslash($_POST['fa_general'])
        : [];
    $apiPost = isset($_POST['fa_api']) && is_array($_POST['fa_api'])
        ? wp_unslash($_POST['fa_api'])
        : [];
    $notificationsPost = isset($_POST['fa_notifications']) && is_array($_POST['fa_notifications'])
        ? wp_unslash($_POST['fa_notifications'])
        : [];
    $currentTab = isset($_GET['tab']) ? sanitize_title(wp_unslash($_GET['tab'])) : '';

    update_option('fa_general', [
        'site_label' => sanitize_text_field((string) ($generalPost['site_label'] ?? '')),
        'timezone' => sanitize_text_field((string) ($generalPost['timezone'] ?? 'UTC')),
    ]);

    update_option('fa_api', [
        'api_key' => sanitize_text_field((string) ($apiPost['api_key'] ?? '')),
        'mode' => sanitize_text_field((string) ($apiPost['mode'] ?? 'test')),
    ]);

    update_option('fa_notifications', [
        'email_enabled' => !empty($notificationsPost['email_enabled']) ? '1' : '0',
    ]);

    $redirectUrl = admin_url('admin.php?page=fa-multi-settings&settings-updated=true');

    if ('' !== $currentTab) {
        $redirectUrl = add_query_arg('tab', $currentTab, $redirectUrl);
    }

    wp_safe_redirect($redirectUrl);
    exit;
}

function fa_render_multi_settings(): void
{
    fa_handle_multi_settings_save();

    $general = (array) get_option('fa_general', ['site_label' => '', 'timezone' => 'UTC']);
    $api = (array) get_option('fa_api', ['api_key' => '', 'mode' => 'test']);
    $notifications = (array) get_option('fa_notifications', ['email_enabled' => '0']);
    $updated = isset($_GET['settings-updated'])
        && 'true' === sanitize_text_field(wp_unslash($_GET['settings-updated']));

    Page::make('Multi-Tab Settings')->render(function () use ($general, $api, $notifications, $updated) {
        if ($updated) {
            echo Notice::make('Settings saved.', 'success')->dismissible();
        }

        echo '<form method="post">';
        wp_nonce_field('fa_multi_save', 'fa_multi_nonce');

        echo Tabs::make()
            ->tab('General', FormTable::make()->text('fa_general[site_label]', 'Site Label', ['value' => (string) $general['site_label']]))
            ->tab('API', FormTable::make()->text('fa_api[api_key]', 'API Key', ['value' => (string) $api['api_key']]))
            ->tab('Notifications', FormTable::make()->checkbox('fa_notifications[email_enabled]', 'Enable Email Alerts', ['checked' => (string) $notifications['email_enabled'] === '1']));

        echo Button::make('Save Settings')->primary()->submit();
        echo '</form>';
    });
}
```
