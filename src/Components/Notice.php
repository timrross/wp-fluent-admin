<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Renders a WordPress admin notice.
 *
 * Output: <div class="notice notice-{type} [is-dismissible] [notice-alt]"><p>{message}</p></div>
 */
class Notice extends Component
{
    protected string $message;
    protected string $type;
    protected bool $dismissible;

    /**
     * @param string $message     The notice text (escaped automatically).
     * @param string $type        One of: info, success, warning, error, default.
     * @param bool   $dismissible Whether to show the dismiss button.
     */
    public function __construct(string $message, string $type = 'info', bool $dismissible = false)
    {
        $this->message = $message;
        $this->type = $type;
        $this->dismissible = $dismissible;
    }

    /**
     * Toggle the dismiss button.
     *
     * @param bool $value
     * @return static
     */
    public function dismissible(bool $value = true): static
    {
        $this->dismissible = $value;
        return $this;
    }

    /**
     * Apply the alt background style.
     *
     * @param bool $value
     * @return static
     */
    public function alt(bool $value = true): static
    {
        $this->config['alt'] = $value;
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $classes = ['notice'];

        if ($this->type !== 'default') {
            $classes[] = 'notice-' . $this->type;
        }

        if ($this->dismissible) {
            $classes[] = 'is-dismissible';
        }

        if (!empty($this->config['alt'])) {
            $classes[] = 'notice-alt';
        }

        $class = Escape::attr(implode(' ', $classes));
        $message = Escape::html($this->message);

        return "<div class=\"{$class}\"><p>{$message}</p></div>";
    }
}
