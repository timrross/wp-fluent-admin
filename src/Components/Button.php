<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Renders a WordPress admin button.
 *
 * Output: <a href="{url}" class="button [...]">{text}</a>
 * Or submit variant: <button type="submit" class="button [...]">{text}</button>
 */
class Button extends Component
{
    protected string $text;
    protected string $url;
    protected bool $isPrimary = false;
    protected bool $isSmall = false;
    protected bool $isHero = false;
    protected bool $isSubmit = false;
    protected bool $isDisabled = false;
    protected bool $newTab = false;

    /**
     * @param string $text The button label (escaped automatically).
     * @param string $url  The href URL for link buttons.
     */
    public function __construct(string $text, string $url = '#')
    {
        $this->text = $text;
        $this->url = $url;
    }

    /**
     * Apply the primary (blue) button style.
     *
     * @return static
     */
    public function primary(): static
    {
        $this->isPrimary = true;
        return $this;
    }

    /**
     * Apply the secondary button style (default).
     *
     * @return static
     */
    public function secondary(): static
    {
        $this->isPrimary = false;
        return $this;
    }

    /**
     * Apply the small button size.
     *
     * @return static
     */
    public function small(): static
    {
        $this->isSmall = true;
        return $this;
    }

    /**
     * Apply the hero (large) button size.
     *
     * @return static
     */
    public function hero(): static
    {
        $this->isHero = true;
        return $this;
    }

    /**
     * Render as a <button type="submit"> instead of <a>.
     *
     * @return static
     */
    public function submit(): static
    {
        $this->isSubmit = true;
        return $this;
    }

    /**
     * Mark the button as disabled.
     *
     * @return static
     */
    public function disabled(): static
    {
        $this->isDisabled = true;
        return $this;
    }

    /**
     * Open link in a new browser tab.
     *
     * @return static
     */
    public function newTab(): static
    {
        $this->newTab = true;
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $classes = ['button'];

        if ($this->isPrimary) {
            $classes[] = 'button-primary';
        }
        if ($this->isSmall) {
            $classes[] = 'button-small';
        }
        if ($this->isHero) {
            $classes[] = 'button-hero';
        }

        $class = Escape::attr(implode(' ', $classes));
        $text = Escape::html($this->text);

        if ($this->isSubmit) {
            $disabled = $this->isDisabled ? ' disabled' : '';
            return "<button type=\"submit\" class=\"{$class}\"{$disabled}>{$text}</button>";
        }

        $href = Escape::url($this->url);
        $disabled = $this->isDisabled ? ' disabled' : '';
        $target = $this->newTab ? ' target="_blank" rel="noopener noreferrer"' : '';

        return "<a href=\"{$href}\" class=\"{$class}\"{$disabled}{$target}>{$text}</a>";
    }
}
