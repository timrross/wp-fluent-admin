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

        // Determine current tab from $_GET or default.
        $getTab = $this->getQueryParam('tab');
        if ($getTab !== '') {
            $getTab = $this->sanitizeTitle($getTab);
        }
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

        $url = $this->getRequestUri();
        $separator = strpos($url, '?') !== false ? '&' : '?';
        return $url . $separator . 'tab=' . rawurlencode($slug);
    }

    /**
     * Safely read a scalar query parameter from $_GET.
     *
     * @param string $key
     * @return string
     */
    protected function getQueryParam(string $key): string
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL state for tab selection.
        if (!isset($_GET[$key])) {
            return '';
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Value is unslashed and sanitized in sanitizeText().
        $value = $_GET[$key];
        $text = is_scalar($value) ? (string) $value : '';

        return $this->sanitizeText($text);
    }

    /**
     * Read and sanitize the current request URI.
     *
     * @return string
     */
    protected function getRequestUri(): string
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return '';
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Request URI is unslashed and sanitized below.
        $uri = is_string($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        $uri = $this->unslash($uri);

        if (function_exists('esc_url_raw')) {
            return (string) esc_url_raw($uri);
        }

        $sanitized = filter_var($uri, FILTER_SANITIZE_URL);
        return is_string($sanitized) ? $sanitized : '';
    }

    /**
     * Unslash a value using WordPress when available.
     *
     * @param string $value
     * @return string
     */
    protected function unslash(string $value): string
    {
        if (function_exists('wp_unslash')) {
            return (string) wp_unslash($value);
        }

        return stripslashes($value);
    }

    /**
     * Sanitize text field content with a non-WordPress fallback.
     *
     * @param string $text
     * @return string
     */
    protected function sanitizeText(string $text): string
    {
        $text = $this->unslash($text);

        if (function_exists('sanitize_text_field')) {
            return sanitize_text_field($text);
        }

        return trim(strip_tags($text));
    }
}
