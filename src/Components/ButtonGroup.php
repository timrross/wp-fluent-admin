<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Traits\HasChildren;

/**
 * Renders a group of buttons.
 *
 * Output: <div class="button-group">{children}</div>
 */
class ButtonGroup extends Component
{
    use HasChildren;

    /**
     * Add one or more Button instances to the group.
     *
     * @param Button ...$buttons
     * @return static
     */
    public function add(Button ...$buttons): static
    {
        foreach ($buttons as $button) {
            $this->child($button);
        }
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        return '<div class="button-group">' . $this->renderChildren() . '</div>';
    }
}
