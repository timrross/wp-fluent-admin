<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Renders the WordPress admin page wrapper.
 *
 * Output: <div class="wrap"><h1>[icon]{title}</h1>{content}</div>
 */
class Page extends Component
{
    protected string $title;

    /** @var callable|null */
    protected $contentCallable = null;

    protected string $icon = '';

    /**
     * @param string $title The page title (escaped automatically).
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * Set the page content via a callable.
     *
     * @param callable $callback
     * @return static
     */
    public function content(callable $callback): static
    {
        $this->contentCallable = $callback;
        return $this;
    }

    /**
     * Set the dashicon displayed before the title.
     *
     * @param string $dashicon Dashicon name (e.g. "dashicons-admin-settings").
     * @return static
     */
    public function icon(string $dashicon): static
    {
        $this->icon = $dashicon;
        return $this;
    }

    /**
     * Dual-purpose render override:
     * - With no arguments: returns rendered HTML string (standard Component behaviour).
     * - With a callable: sets content, immediately echoes the rendered page, and returns ''.
     *
     * @param callable|null $callback
     * @return string
     */
    public function render(?callable $callback = null): string
    {
        if ($callback !== null) {
            $this->content($callback);
            echo $this->html();
            return '';
        }

        return parent::render();
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $title = Escape::html($this->title);

        $iconHtml = '';
        if ($this->icon !== '') {
            $icon = Escape::attr($this->icon);
            $iconHtml = '<span class="dashicons ' . $icon . '"></span> ';
        }

        $content = '';
        if ($this->contentCallable !== null) {
            ob_start();
            ($this->contentCallable)();
            $content = (string) ob_get_clean();
        }

        return '<div class="wrap">'
            . '<h1>' . $iconHtml . $title . '</h1>'
            . $content
            . '</div>';
    }
}
