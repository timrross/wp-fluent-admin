# Building Custom Components

Build your own component by extending `Component`, then use it in a real admin page.

## What we're building

- A reusable `StatusCard` component
- Configurable status type (`ok`, `warning`, `error`)
- Use of component filters for customization

## Step 1: Create a custom component

```php
namespace MyPlugin\Admin;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

class StatusCard extends Component
{
    protected string $title;
    protected string $message = '';
    protected string $status = 'ok';

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function status(string $status): self
    {
        $allowed = ['ok', 'warning', 'error'];
        $this->status = in_array($status, $allowed, true) ? $status : 'ok';
        return $this;
    }

    protected function html(): string
    {
        return '<div class="card status-card status-' . Escape::attr($this->status) . '">'
            . '<h2 class="title">' . Escape::html($this->title) . '</h2>'
            . '<p>' . Escape::html($this->message) . '</p>'
            . '</div>';
    }
}
```

## Step 2: Use it in an admin page

```php
use FluentAdmin\Components\Page;
use MyPlugin\Admin\StatusCard;

add_action('admin_menu', function () {
    add_menu_page('System Status', 'System Status', 'manage_options', 'fa-status', function () {
        Page::make('System Status')->render(function () {
            echo StatusCard::make('API Connection')
                ->status('warning')
                ->message('Last sync failed 5 minutes ago.');
        });
    }, 'dashicons-heart');
});
```

## Step 3: Modify output via filter

```php
add_filter('fluent_admin_statuscard_render', function (string $html, array $config): string {
    return str_replace('status-card', 'status-card my-status-card', $html);
}, 10, 2);
```

## Complete code

```php
<?php
/**
 * Plugin Name: FA Custom Component Guide
 */

declare(strict_types=1);

namespace MyPlugin\Admin {
    use FluentAdmin\Component;
    use FluentAdmin\Support\Escape;

    class StatusCard extends Component
    {
        protected string $title;
        protected string $message = '';
        protected string $status = 'ok';

        public function __construct(string $title)
        {
            $this->title = $title;
        }

        public function message(string $message): self
        {
            $this->message = $message;
            return $this;
        }

        public function status(string $status): self
        {
            $allowed = ['ok', 'warning', 'error'];
            $this->status = in_array($status, $allowed, true) ? $status : 'ok';
            return $this;
        }

        protected function html(): string
        {
            return '<div class="card status-card status-' . Escape::attr($this->status) . '">'
                . '<h2 class="title">' . Escape::html($this->title) . '</h2>'
                . '<p>' . Escape::html($this->message) . '</p>'
                . '</div>';
        }
    }
}

namespace {
    require_once __DIR__ . '/vendor/autoload.php';

    use FluentAdmin\Components\Page;
    use MyPlugin\Admin\StatusCard;

    add_action('admin_menu', function () {
        add_menu_page('System Status', 'System Status', 'manage_options', 'fa-status', function () {
            Page::make('System Status')->render(function () {
                echo StatusCard::make('API Connection')->status('warning')->message('Last sync failed 5 minutes ago.');
            });
        }, 'dashicons-heart');
    });

    add_filter('fluent_admin_statuscard_render', function (string $html, array $config): string {
        return str_replace('status-card', 'status-card my-status-card', $html);
    }, 10, 2);
}
```
