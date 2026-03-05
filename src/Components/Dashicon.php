<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Renders a WordPress dashicon.
 *
 * Output: <span class="dashicons dashicons-{icon}"></span>
 *
 * If $icon already starts with "dashicons-", it is not double-prefixed.
 */
class Dashicon extends Component
{
    protected string $icon;

    /**
     * @param string $icon The dashicon name (e.g. "admin-settings" or "dashicons-admin-settings").
     */
    public function __construct(string $icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $icon = $this->icon;

        if (strpos($icon, 'dashicons-') !== 0) {
            $icon = 'dashicons-' . $icon;
        }

        return '<span class="dashicons ' . Escape::attr($icon) . '"></span>';
    }
}
