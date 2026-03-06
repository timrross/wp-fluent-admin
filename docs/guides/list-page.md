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

    $bulkAction = isset($_POST['action']) ? sanitize_text_field(wp_unslash($_POST['action'])) : '';
    $bulkAction2 = isset($_POST['action2']) ? sanitize_text_field(wp_unslash($_POST['action2'])) : '';
    $bulkNonce = isset($_POST['fa_contacts_bulk_nonce']) ? sanitize_text_field(wp_unslash($_POST['fa_contacts_bulk_nonce'])) : '';
    $bulkIds = isset($_POST['bulk-ids']) && is_array($_POST['bulk-ids'])
        ? array_filter(array_map('absint', wp_unslash($_POST['bulk-ids'])))
        : [];
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    $id = isset($_GET['id']) ? absint(wp_unslash($_GET['id'])) : 0;
    $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
    $deleted = isset($_GET['deleted']) && '1' === sanitize_text_field(wp_unslash($_GET['deleted']));
    $searchTerm = isset($_REQUEST['s']) ? sanitize_text_field(wp_unslash($_REQUEST['s'])) : '';

    if (
        ('delete' === $bulkAction || 'delete' === $bulkAction2)
        && [] !== $bulkIds
        && wp_verify_nonce($bulkNonce, 'fa_contacts_bulk_delete')
    ) {
        foreach ($bulkIds as $bulkId) {
            $wpdb->delete($tableName, ['id' => $bulkId], ['%d']);
        }

        wp_safe_redirect(admin_url('admin.php?page=fa-contacts&deleted=1'));
        exit;
    }

    if ('delete' === $action && $id > 0 && wp_verify_nonce($nonce, 'fa_delete_contact_' . $id)) {
        $wpdb->delete($tableName, ['id' => $id], ['%d']);
        wp_safe_redirect(admin_url('admin.php?page=fa-contacts&deleted=1'));
        exit;
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
        ->rowActions(function (array $item): array {
            $id = (int) ($item['id'] ?? 0);

            if ($id <= 0) {
                return [];
            }

            return [
                'delete' => sprintf(
                    '<a href="%s">Delete</a>',
                    esc_url(
                        wp_nonce_url(
                            admin_url('admin.php?page=fa-contacts&action=delete&id=' . $id),
                            'fa_delete_contact_' . $id
                        )
                    )
                ),
            ];
        })
        ->count(function () use ($wpdb, $tableName, $searchTerm): int {
            if ('' === $searchTerm) {
                return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$tableName}");
            }

            $sql = $wpdb->prepare(
                "SELECT COUNT(*) FROM {$tableName} WHERE email LIKE %s",
                '%' . $wpdb->esc_like($searchTerm) . '%'
            );

            return (int) $wpdb->get_var($sql);
        })
        ->data(function (array $args) use ($wpdb, $tableName, $searchTerm): array {
            $perPage = (int) $args['per_page'];
            $page = max(1, (int) $args['page']);
            $offset = ($page - 1) * $perPage;

            $allowed = ['email', 'status', 'created_at'];
            $orderby = in_array($args['orderby'], $allowed, true) ? $args['orderby'] : 'created_at';
            $order = strtoupper((string) $args['order']) === 'ASC' ? 'ASC' : 'DESC';

            if ('' === $searchTerm) {
                $sql = $wpdb->prepare(
                    "SELECT id, email, status, created_at FROM {$tableName} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
                    $perPage,
                    $offset
                );
            } else {
                $sql = $wpdb->prepare(
                    "SELECT id, email, status, created_at FROM {$tableName} WHERE email LIKE %s ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
                    '%' . $wpdb->esc_like($searchTerm) . '%',
                    $perPage,
                    $offset
                );
            }

            $rows = (array) $wpdb->get_results($sql, ARRAY_A);

            return $rows;
        });

    Page::make('Contacts')->render(function () use ($list, $deleted) {
        if ($deleted) {
            echo Notice::make('Contact deleted.', 'success')->dismissible();
        }

        echo '<form method="post">';
        wp_nonce_field('fa_contacts_bulk_delete', 'fa_contacts_bulk_nonce');
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

    $bulkAction = isset($_POST['action']) ? sanitize_text_field(wp_unslash($_POST['action'])) : '';
    $bulkAction2 = isset($_POST['action2']) ? sanitize_text_field(wp_unslash($_POST['action2'])) : '';
    $bulkNonce = isset($_POST['fa_contacts_bulk_nonce']) ? sanitize_text_field(wp_unslash($_POST['fa_contacts_bulk_nonce'])) : '';
    $bulkIds = isset($_POST['bulk-ids']) && is_array($_POST['bulk-ids'])
        ? array_filter(array_map('absint', wp_unslash($_POST['bulk-ids'])))
        : [];
    $action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
    $id = isset($_GET['id']) ? absint(wp_unslash($_GET['id'])) : 0;
    $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';
    $deleted = isset($_GET['deleted']) && '1' === sanitize_text_field(wp_unslash($_GET['deleted']));
    $searchTerm = isset($_REQUEST['s']) ? sanitize_text_field(wp_unslash($_REQUEST['s'])) : '';

    if (
        ('delete' === $bulkAction || 'delete' === $bulkAction2)
        && [] !== $bulkIds
        && wp_verify_nonce($bulkNonce, 'fa_contacts_bulk_delete')
    ) {
        foreach ($bulkIds as $bulkId) {
            $wpdb->delete($tableName, ['id' => $bulkId], ['%d']);
        }

        wp_safe_redirect(admin_url('admin.php?page=fa-contacts&deleted=1'));
        exit;
    }

    if ('delete' === $action && $id > 0 && wp_verify_nonce($nonce, 'fa_delete_contact_' . $id)) {
        $wpdb->delete($tableName, ['id' => $id], ['%d']);
        wp_safe_redirect(admin_url('admin.php?page=fa-contacts&deleted=1'));
        exit;
    }

    $list = ListTable::make()
        ->columns(['id' => 'ID', 'email' => 'Email', 'status' => 'Status', 'created_at' => 'Created'])
        ->sortable(['email', 'status', 'created_at'])
        ->bulkActions(['delete' => 'Delete'])
        ->perPage(20)
        ->search()
        ->rowActions(function (array $item): array {
            $id = (int) ($item['id'] ?? 0);

            if ($id <= 0) {
                return [];
            }

            return [
                'delete' => sprintf(
                    '<a href="%s">Delete</a>',
                    esc_url(
                        wp_nonce_url(
                            admin_url('admin.php?page=fa-contacts&action=delete&id=' . $id),
                            'fa_delete_contact_' . $id
                        )
                    )
                ),
            ];
        })
        ->count(function () use ($wpdb, $tableName, $searchTerm): int {
            if ('' === $searchTerm) {
                return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$tableName}");
            }

            $sql = $wpdb->prepare(
                "SELECT COUNT(*) FROM {$tableName} WHERE email LIKE %s",
                '%' . $wpdb->esc_like($searchTerm) . '%'
            );

            return (int) $wpdb->get_var($sql);
        })
        ->data(function (array $args) use ($wpdb, $tableName, $searchTerm): array {
            $perPage = (int) $args['per_page'];
            $page = max(1, (int) $args['page']);
            $offset = ($page - 1) * $perPage;
            $allowed = ['email', 'status', 'created_at'];
            $orderby = in_array($args['orderby'], $allowed, true) ? $args['orderby'] : 'created_at';
            $order = strtoupper((string) $args['order']) === 'ASC' ? 'ASC' : 'DESC';

            if ('' === $searchTerm) {
                $sql = $wpdb->prepare(
                    "SELECT id, email, status, created_at FROM {$tableName} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
                    $perPage,
                    $offset
                );
            } else {
                $sql = $wpdb->prepare(
                    "SELECT id, email, status, created_at FROM {$tableName} WHERE email LIKE %s ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
                    '%' . $wpdb->esc_like($searchTerm) . '%',
                    $perPage,
                    $offset
                );
            }

            return (array) $wpdb->get_results($sql, ARRAY_A);
        });

    Page::make('Contacts')->render(function () use ($list, $deleted) {
        if ($deleted) {
            echo Notice::make('Contact deleted.', 'success')->dismissible();
        }

        echo '<form method="post">';
        wp_nonce_field('fa_contacts_bulk_delete', 'fa_contacts_bulk_nonce');
        echo $list;
        echo '</form>';
    });
}
```
