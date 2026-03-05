<?php

declare(strict_types=1);

namespace FluentAdmin\Components;

use FluentAdmin\Component;
use FluentAdmin\Support\Escape;

/**
 * Renders a WordPress nav-tab-wrapper with URL-based active state.
 *
 * Active tab determined from $_GET['tab'], falling back to the first tab.
 * Tab slugs generated via sanitize_title().
 */
class Tabs extends Component
{
    /** @var array<string, callable|string|Component> label => content */
    protected array $tabs = [];

    /** @var string|null Explicitly set active tab label */
    protected ?string $activeLabel = null;

    /**
     * Add a tab.
     *
     * @param string                    $label   The visible tab label.
     * @param callable|string|Component $content The tab panel content.
     * @return static
     */
    public function tab(string $label, $content): static
    {
        $this->tabs[$label] = $content;
        return $this;
    }

    /**
     * Set the default active tab by label.
     *
     * @param string $label
     * @return static
     */
    public function active(string $label): static
    {
        $this->activeLabel = $label;
        return $this;
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        if (empty($this->tabs)) {
            return '';
        }

        // Build slug map
        $slugs = [];
        foreach (array_keys($this->tabs) as $label) {
            $slugs[$label] = $this->sanitizeTitle($label);
        }

        // Determine current tab from $_GET or default
        $getTab = isset($_GET['tab']) ? $this->sanitizeTitle((string) $_GET['tab']) : '';
        $slugValues = array_values($slugs);

        if ($getTab !== '' && in_array($getTab, $slugValues, true)) {
            $currentSlug = $getTab;
        } else {
            // Fall back to explicitly set active label, or first tab
            $defaultLabel = $this->activeLabel ?? array_key_first($this->tabs);
            $currentSlug = $slugs[$defaultLabel] ?? reset($slugValues);
        }

        // Navigation
        $nav = '<h2 class="nav-tab-wrapper">';
        foreach ($this->tabs as $label => $content) {
            $slug = $slugs[$label];
            $activeClass = ($slug === $currentSlug) ? ' nav-tab-active' : '';
            $url = $this->buildUrl($slug);
            $nav .= sprintf(
                '<a href="%s" class="nav-tab%s">%s</a>',
                Escape::url($url),
                Escape::attr($activeClass),
                Escape::html($label)
            );
        }
        $nav .= '</h2>';

        // Active tab content
        $activeLabel = (string) array_search($currentSlug, $slugs, true);
        $activeContent = $this->tabs[$activeLabel] ?? '';

        $body = '<div class="tab-content">';
        $body .= $this->resolveContent($activeContent);
        $body .= '</div>';

        return $nav . $body;
    }

    /**
     * Sanitize a string to a URL-safe slug.
     *
     * @param string $title
     * @return string
     */
    protected function sanitizeTitle(string $title): string
    {
        if (function_exists('sanitize_title')) {
            return sanitize_title($title);
        }

        $title = strtolower($title);
        $title = preg_replace('/[^a-z0-9\-]/', '-', $title) ?? $title;
        return trim($title, '-');
    }

    /**
     * Build the URL for a tab link.
     *
     * @param string $slug
     * @return string
     */
    protected function buildUrl(string $slug): string
    {
        if (function_exists('add_query_arg')) {
            return add_query_arg('tab', $slug);
        }

        $url = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
        $separator = strpos($url, '?') !== false ? '&' : '?';
        return $url . $separator . 'tab=' . rawurlencode($slug);
    }
}
