# Quick Start

These examples are progressive. Each one is a complete `add_menu_page()` callback you can copy into a plugin.

## 1. Hello World (Page + Notice + Button)

```php
use FluentAdmin\Components\{Page, Notice, Button};

add_action('admin_menu', function () {
    add_menu_page('FA Demo', 'FA Demo', 'manage_options', 'fa-demo', function () {
        Page::make('Fluent Admin Demo')->render(function () {
            echo Notice::make('Hello from wp-fluent-admin.', 'success')->dismissible();
            echo Button::make('Read the docs', 'https://github.com/wp-fluent-admin/wp-fluent-admin')->primary()->newTab();
        });
    }, 'dashicons-admin-generic');
});
```

## 2. Settings Form (Page + Metabox + FormTable + `wp_options`)

```php
use FluentAdmin\Components\{Page, Metabox, FormTable, Button};

add_action('admin_menu', function () {
    add_menu_page('FA Settings', 'FA Settings', 'manage_options', 'fa-settings', function () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fa_nonce']) && wp_verify_nonce(sanitize_text_field((string) $_POST['fa_nonce']), 'fa_save')) {
            update_option('fa_api_key', sanitize_text_field((string) ($_POST['fa_api_key'] ?? '')));
            update_option('fa_env', sanitize_text_field((string) ($_POST['fa_env'] ?? 'prod')));
        }

        $apiKey = (string) get_option('fa_api_key', '');
        $env = (string) get_option('fa_env', 'prod');

        Page::make('FA Settings')->render(function () use ($apiKey, $env) {
            echo '<form method="post">';
            wp_nonce_field('fa_save', 'fa_nonce');
            echo Metabox::make('API Settings')->content(
                FormTable::make()
                    ->text('fa_api_key', 'API Key', ['value' => $apiKey, 'placeholder' => 'pk_live_...'])
                    ->select('fa_env', 'Environment', ['prod' => 'Production', 'dev' => 'Development'], ['value' => $env])
            );
            echo Button::make('Save Settings')->primary()->submit();
            echo '</form>';
        });
    }, 'dashicons-admin-tools');
});
```

## 3. Data Listing (Page + Tabs + ListTable with sorting)

```php
use FluentAdmin\Components\{Page, Tabs, ListTable};

add_action('admin_menu', function () {
    add_menu_page('FA Logs', 'FA Logs', 'manage_options', 'fa-logs', function () {
        $rows = [
            ['id' => 1, 'event' => 'Sync completed', 'status' => 'success'],
            ['id' => 2, 'event' => 'Token expired', 'status' => 'error'],
            ['id' => 3, 'event' => 'Retry scheduled', 'status' => 'warning'],
        ];

        $table = ListTable::make()
            ->columns(['event' => 'Event', 'status' => 'Status'])
            ->sortable(['event', 'status'])
            ->bulkActions(['delete' => 'Delete'])
            ->perPage(20)
            ->search()
            ->count(function (): int { return 3; })
            ->data(function (array $args) use ($rows): array {
                $orderby = $args['orderby'] ?: 'event';
                $order = strtolower((string) ($args['order'] ?? 'asc')) === 'desc' ? -1 : 1;
                usort($rows, function (array $a, array $b) use ($orderby, $order): int {
                    return $order * strcmp((string) ($a[$orderby] ?? ''), (string) ($b[$orderby] ?? ''));
                });
                return $rows;
            });

        Page::make('FA Logs')->render(function () use ($table) {
            echo Tabs::make()
                ->tab('All Events', $table)
                ->tab('Settings', function () {
                    echo '<p>Configure retention and export options.</p>';
                });
        });
    }, 'dashicons-list-view');
});
```
