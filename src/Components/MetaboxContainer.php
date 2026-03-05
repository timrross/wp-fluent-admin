<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;

/**
 * Renders a two-column metabox layout (the WordPress post editor layout).
 *
 * Output:
 * <div id="poststuff">
 *   <div id="post-body" class="metabox-holder columns-{n}">
 *     <div id="post-body-content">{primary}</div>
 *     <div id="postbox-container-1" class="postbox-container">{sidebar}</div>
 *   </div>
 * </div>
 */
class MetaboxContainer extends Component
{
    protected int $columns = 2;

    /** @var callable|Component|string|null */
    protected $primaryContent = null;

    /** @var callable|Component|string|null */
    protected $sidebarContent = null;

    /**
     * Set the number of columns (1 or 2).
     *
     * @param int $count
     * @return static
     */
    public function columns(int $count = 2): static
    {
        $this->columns = $count;
        return $this;
    }

    /**
     * Set the primary (main) area content.
     *
     * @param callable|Component|string $content
     * @return static
     */
    public function primary($content): static
    {
        $this->primaryContent = $content;
        return $this;
    }

    /**
     * Set the sidebar area content.
     *
     * @param callable|Component|string $content
     * @return static
     */
    public function sidebar($content): static
    {
        $this->sidebarContent = $content;
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $primary = $this->primaryContent !== null ? $this->resolveContent($this->primaryContent) : '';
        $sidebar = $this->sidebarContent !== null ? $this->resolveContent($this->sidebarContent) : '';

        return '<div id="poststuff">'
            . '<div id="post-body" class="metabox-holder columns-' . $this->columns . '">'
            . '<div id="post-body-content">' . $primary . '</div>'
            . '<div id="postbox-container-1" class="postbox-container">' . $sidebar . '</div>'
            . '</div>'
            . '</div>';
    }
}
