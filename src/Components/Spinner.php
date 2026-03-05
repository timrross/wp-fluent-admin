<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;

/**
 * Renders a WordPress admin loading spinner.
 *
 * Output: <span class="spinner [is-active]"></span>
 */
class Spinner extends Component
{
    protected bool $active;

    /**
     * @param bool $active Whether the spinner is visible/animating.
     */
    public function __construct(bool $active = true)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $class = 'spinner' . ($this->active ? ' is-active' : '');
        return '<span class="' . $class . '"></span>';
    }
}
