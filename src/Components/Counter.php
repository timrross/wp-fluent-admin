<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Renders a count bubble (like the plugin update count in the menu).
 *
 * Standard: <span class="count">({count})</span>
 * Menu style: <span class="update-plugins count-{n}"><span class="plugin-count">{n}</span></span>
 */
class Counter extends Component
{
    protected int $count;
    protected bool $menuStyle = false;

    /**
     * @param int $count The number to display.
     */
    public function __construct(int $count)
    {
        $this->count = $count;
    }

    /**
     * Use the menu update-plugins bubble variant.
     *
     * @return static
     */
    public function menuStyle(): static
    {
        $this->menuStyle = true;
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $n = $this->count;

        if ($this->menuStyle) {
            $nEscaped = Escape::html((string) $n);
            return '<span class="update-plugins count-' . Escape::attr((string) $n) . '">'
                . '<span class="plugin-count">' . $nEscaped . '</span>'
                . '</span>';
        }

        return '<span class="count">(' . Escape::html((string) $n) . ')</span>';
    }
}
