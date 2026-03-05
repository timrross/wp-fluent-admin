# Building a List Page

Build a list-management admin page backed by a custom database table.

## What we're building

- A top-level admin menu page
- A custom table in `$wpdb`
- A `ListTable` with sorting, bulk actions, and search UI
- Delete handling with nonces

## Step 1: Create the data table

```php
register_activation_hook(__FILE__, function () {
    global $wpdb;

    $table = $wpdb->prefix . 'fa_contacts';
    $charsetCollate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL,
        status VARCHAR(32) NOT NULL DEFAULT 'active',
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) {$charsetCollate};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
});
```

## Step 2: Render the list table

```php
use FluentAdmin\Components\{Page, Notice, ListTable};

function fa_render_contacts_page(): void
{
    global $wpdb;
    $tableName = $wpdb->prefix . 'fa_contacts';

    if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
        $id = absint($_GET['id']);
        $nonce = sanitize_text_field((string) ($_GET['_wpnonce'] ?? ''));

        if ($id > 0 && wp_verify_nonce($nonce, 'fa_delete_contact_' . $id)) {
            $wpdb->delete($tableName, ['id' => $id], ['%d']);
            wp_safe_redirect(admin_url('admin.php?page=fa-contacts&deleted=1'));
            exit;
        }
    }

    $list = ListTable::make()
        ->columns([
            'id' => 'ID',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created',
        ])
        ->sortable(['email', 'status', 'created_at'])
        ->bulkActions(['delete' => 'Delete'])
        ->perPage(20)
        ->search()
        ->count(function () use ($wpdb, $tableName): int {
            return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$tableName}");
        })
        ->data(function (array $args) use ($wpdb, $tableName): array {
            $perPage = (int) $args['per_page'];
            $page = max(1, (int) $args['page']);
            $offset = ($page - 1) * $perPage;

            $allowed = ['email', 'status', 'created_at'];
            $orderby = in_array($args['orderby'], $allowed, true) ? $args['orderby'] : 'created_at';
            $order = strtoupper((string) $args['order']) === 'ASC' ? 'ASC' : 'DESC';

            $sql = $wpdb->prepare(
                "SELECT id, email, status, created_at FROM {$tableName} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
                $perPage,
                $offset
            );

            $rows = (array) $wpdb->get_results($sql, ARRAY_A);

            foreach ($rows as &$row) {
                $deleteUrl = wp_nonce_url(
                    admin_url('admin.php?page=fa-contacts&action=delete&id=' . (int) $row['id']),
                    'fa_delete_contact_' . (int) $row['id']
                );
                $row['email'] = $row['email'] . ' (delete: ' . $deleteUrl . ')';
            }

            return $rows;
        });

    Page::make('Contacts')->render(function () use ($list) {
        if (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
            echo Notice::make('Contact deleted.', 'success')->dismissible();
        }

        echo '<form method="post">';
        echo $list;
        echo '</form>';
    });
}
```

## Step 3: Register the menu page

```php
add_action('admin_menu', function () {
    add_menu_page(
        'Contacts',
        'Contacts',
        'manage_options',
        'fa-contacts',
        'fa_render_contacts_page',
        'dashicons-id'
    );
});
```

## Complete code

```php
<?php
/**
 * Plugin Name: FA List Page Guide
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use FluentAdmin\Components\{Page, Notice, ListTable};

register_activation_hook(__FILE__, function () {
    global $wpdb;

    $table = $wpdb->prefix . 'fa_contacts';
    $charsetCollate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL,
        status VARCHAR(32) NOT NULL DEFAULT 'active',
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) {$charsetCollate};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
});

add_action('admin_menu', function () {
    add_menu_page('Contacts', 'Contacts', 'manage_options', 'fa-contacts', 'fa_render_contacts_page', 'dashicons-id');
});

function fa_render_contacts_page(): void
{
    global $wpdb;
    $tableName = $wpdb->prefix . 'fa_contacts';

    if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
        $id = absint($_GET['id']);
        $nonce = sanitize_text_field((string) ($_GET['_wpnonce'] ?? ''));

        if ($id > 0 && wp_verify_nonce($nonce, 'fa_delete_contact_' . $id)) {
            $wpdb->delete($tableName, ['id' => $id], ['%d']);
            wp_safe_redirect(admin_url('admin.php?page=fa-contacts&deleted=1'));
            exit;
        }
    }

    $list = ListTable::make()
        ->columns(['id' => 'ID', 'email' => 'Email', 'status' => 'Status', 'created_at' => 'Created'])
        ->sortable(['email', 'status', 'created_at'])
        ->bulkActions(['delete' => 'Delete'])
        ->perPage(20)
        ->search()
        ->count(function () use ($wpdb, $tableName): int {
            return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$tableName}");
        })
        ->data(function (array $args) use ($wpdb, $tableName): array {
            $perPage = (int) $args['per_page'];
            $page = max(1, (int) $args['page']);
            $offset = ($page - 1) * $perPage;
            $allowed = ['email', 'status', 'created_at'];
            $orderby = in_array($args['orderby'], $allowed, true) ? $args['orderby'] : 'created_at';
            $order = strtoupper((string) $args['order']) === 'ASC' ? 'ASC' : 'DESC';
            $sql = $wpdb->prepare(
                "SELECT id, email, status, created_at FROM {$tableName} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
                $perPage,
                $offset
            );
            return (array) $wpdb->get_results($sql, ARRAY_A);
        });

    Page::make('Contacts')->render(function () use ($list) {
        if (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
            echo Notice::make('Contact deleted.', 'success')->dismissible();
        }

        echo '<form method="post">';
        echo $list;
        echo '</form>';
    });
}
```
