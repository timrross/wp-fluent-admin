<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Renders a WordPress card-style panel.
 *
 * Output: <div class="card"><h2 class="title">{title}</h2>{content}{footer}</div>
 * If no title is set, the <h2> is omitted.
 */
class Card extends Component
{
    protected string $title;

    /** @var callable|Component|string|null */
    protected $contentValue = null;

    /** @var callable|Component|string|null */
    protected $footerValue = null;

    /**
     * @param string $title The card title (optional, escaped automatically).
     */
    public function __construct(string $title = '')
    {
        $this->title = $title;
    }

    /**
     * Set the card body content.
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
     * Set the card footer content.
     *
     * @param callable|Component|string $footer
     * @return static
     */
    public function footer($footer): static
    {
        $this->footerValue = $footer;
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $titleHtml = '';
        if ($this->title !== '') {
            $titleHtml = '<h2 class="title">' . Escape::html($this->title) . '</h2>';
        }

        $contentHtml = $this->contentValue !== null ? $this->resolveContent($this->contentValue) : '';
        $footerHtml = $this->footerValue !== null ? $this->resolveContent($this->footerValue) : '';

        return '<div class="card">' . $titleHtml . $contentHtml . $footerHtml . '</div>';
    }
}
