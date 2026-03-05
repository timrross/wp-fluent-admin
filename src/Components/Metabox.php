<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Renders a WordPress postbox (collapsible panel).
 *
 * Output: <div class="postbox [closed]" id="{id}">
 *             <div class="postbox-header"><h2>{title}</h2></div>
 *             <div class="inside">{content}</div>
 *         </div>
 */
class Metabox extends Component
{
    protected string $title;

    /** @var callable|Component|string|null */
    protected $contentValue = null;

    protected bool $closed = false;

    /**
     * @param string $title The metabox title (escaped automatically).
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * Set the metabox content.
     *
     * @param callable|Component|string $content
     * @return static
     */
    public function content($content): static
    {
        $this->contentValue = $content;
        return $this;
    }

    /**
     * Override id() from HasAttributes to also store in config.
     *
     * @param string $id
     * @return static
     */
    public function id(string $id): static
    {
        $this->attributes['id'] = $id;
        $this->config['id'] = $id;
        return $this;
    }

    /**
     * Render the metabox in the closed (collapsed) state.
     *
     * @return static
     */
    public function closed(): static
    {
        $this->closed = true;
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $classes = ['postbox'];
        if ($this->closed) {
            $classes[] = 'closed';
        }
        $class = Escape::attr(implode(' ', $classes));

        $idAttr = '';
        if (!empty($this->attributes['id'])) {
            $idAttr = ' id="' . Escape::attr((string) $this->attributes['id']) . '"';
        }

        $title = Escape::html($this->title);
        $content = $this->contentValue !== null ? $this->resolveContent($this->contentValue) : '';

        return '<div class="' . $class . '"' . $idAttr . '>'
            . '<div class="postbox-header"><h2 class="hndle">' . $title . '</h2></div>'
            . '<div class="inside">' . $content . '</div>'
            . '</div>';
    }
}
