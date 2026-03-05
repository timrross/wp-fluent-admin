# Building a Dashboard Page

Build a dashboard-style admin page with cards, metabox columns, a recent-items table, and quick actions.

## What we're building

- `Page` wrapper with icon
- `MetaboxContainer` two-column layout
- `Card` summary widgets
- `DataTable` for recent activity
- `ButtonGroup` quick actions

## Step 1: Register the page

```php
add_action('admin_menu', function () {
    add_menu_page(
        'Operations Dashboard',
        'Operations',
        'manage_options',
        'fa-dashboard',
        'fa_render_dashboard',
        'dashicons-chart-area'
    );
});
```

## Step 2: Build the dashboard widgets

```php
use FluentAdmin\Components\{Page, Metabox, MetaboxContainer, Card, DataTable, Button, ButtonGroup, Counter};

function fa_render_dashboard(): void
{
    $recentRows = [
        ['event' => 'Sync completed', 'time' => '2026-03-05 10:20'],
        ['event' => 'Feed imported', 'time' => '2026-03-05 09:12'],
        ['event' => 'Worker restarted', 'time' => '2026-03-05 08:55'],
    ];

    $stats =
        Card::make('Queued Jobs')->content((string) Counter::make(9)) .
        Card::make('Failed Jobs')->content((string) Counter::make(2)) .
        Card::make('Last Sync')->content('5 minutes ago');

    $table = DataTable::make(['event' => 'Event', 'time' => 'Time'])
        ->striped()
        ->rows($recentRows);

    $actions = ButtonGroup::make()->add(
        Button::make('Run Sync')->primary(),
        Button::make('Open Logs', admin_url('admin.php?page=fa-logs')),
        Button::make('Settings', admin_url('admin.php?page=fa-settings'))
    );

    Page::make('Operations Dashboard')->icon('dashicons-chart-area')->render(function () use ($stats, $table, $actions) {
        echo MetaboxContainer::make()
            ->primary(Metabox::make('Overview')->content($stats . $table))
            ->sidebar(Metabox::make('Quick Actions')->content($actions));
    });
}
```

## Complete code

```php
<?php
/**
 * Plugin Name: FA Dashboard Guide
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use FluentAdmin\Components\{Page, Metabox, MetaboxContainer, Card, DataTable, Button, ButtonGroup, Counter};

add_action('admin_menu', function () {
    add_menu_page('Operations Dashboard', 'Operations', 'manage_options', 'fa-dashboard', 'fa_render_dashboard', 'dashicons-chart-area');
});

function fa_render_dashboard(): void
{
    $recentRows = [
        ['event' => 'Sync completed', 'time' => '2026-03-05 10:20'],
        ['event' => 'Feed imported', 'time' => '2026-03-05 09:12'],
        ['event' => 'Worker restarted', 'time' => '2026-03-05 08:55'],
    ];

    $stats =
        Card::make('Queued Jobs')->content((string) Counter::make(9)) .
        Card::make('Failed Jobs')->content((string) Counter::make(2)) .
        Card::make('Last Sync')->content('5 minutes ago');

    $table = DataTable::make(['event' => 'Event', 'time' => 'Time'])->striped()->rows($recentRows);

    $actions = ButtonGroup::make()->add(
        Button::make('Run Sync')->primary(),
        Button::make('Open Logs', admin_url('admin.php?page=fa-logs')),
        Button::make('Settings', admin_url('admin.php?page=fa-settings'))
    );

    Page::make('Operations Dashboard')->icon('dashicons-chart-area')->render(function () use ($stats, $table, $actions) {
        echo MetaboxContainer::make()
            ->primary(Metabox::make('Overview')->content($stats . $table))
            ->sidebar(Metabox::make('Quick Actions')->content($actions));
    });
}
```
