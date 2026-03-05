# Documentation Strategy — wp-fluent-admin

## Principles

1. **Every component page is a standalone recipe.** A developer should be able to land on any page, copy an example, and have working code. No prerequisite reading required.
2. **Show, don't tell.** Lead with code. Explanation follows.
3. **Three levels of depth.** Every component page has: a quick example (5 seconds to copy), a full API reference (30 seconds to find a method), and an advanced section (5 minutes to learn patterns).
4. **Real-world over abstract.** Examples should model actual plugin scenarios (settings pages, log viewers, data import screens) — not Foo/Bar demos.
5. **Searchable.** Every method name, class name, and WordPress CSS class should be findable via site search.

---

## Documentation Platform

**VitePress** — lightweight, fast, Markdown-based, built-in search, no heavy framework dependency. The docs source lives in `docs/` in the repo and deploys to GitHub Pages via CI.

```
docs/
├── .vitepress/
│   └── config.ts
├── index.md                     # Landing / hero page
├── getting-started/
│   ├── installation.md
│   ├── quick-start.md
│   └── concepts.md
├── components/
│   ├── page.md
│   ├── notice.md
│   ├── button.md
│   ├── button-group.md
│   ├── metabox.md
│   ├── metabox-container.md
│   ├── tabs.md
│   ├── form-table.md
│   ├── list-table.md
│   ├── data-table.md
│   ├── spinner.md
│   ├── counter.md
│   ├── dashicon.md
│   └── card.md
├── fields/
│   ├── overview.md
│   ├── text.md
│   ├── textarea.md
│   ├── select.md
│   ├── checkbox.md
│   ├── radio.md
│   ├── password.md
│   ├── color.md
│   └── media.md
├── guides/
│   ├── settings-page.md
│   ├── list-page.md
│   ├── dashboard-page.md
│   ├── multi-tab-settings.md
│   └── custom-components.md
├── extensibility/
│   ├── custom-components.md
│   ├── custom-fields.md
│   ├── filters.md
│   └── php-scoper.md
├── reference/
│   ├── component-base.md
│   ├── traits.md
│   ├── support-classes.md
│   └── wordpress-markup.md
└── changelog.md
```

---

## Page Templates

### Landing Page (`index.md`)

The hero page. Must answer in 10 seconds: what is this, why should I care, show me.

```markdown
# wp-fluent-admin

A fluent PHP component library for WordPress admin pages.  
Build native-looking admin UI without hand-coding HTML boilerplate.

:::code-group
```php [Before — 47 lines of boilerplate]
<div class="wrap">
    <h1>My Plugin</h1>
    <?php if (isset($_GET['settings-updated'])): ?>
        <div class="notice notice-success is-dismissible">
            <p>Settings saved.</p>
        </div>
    <?php endif; ?>
    <div class="postbox">
        <div class="postbox-header">
            <h2>API Settings</h2>
        </div>
        <div class="inside">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="api_key">API Key</label></th>
                    <td><input type="text" id="api_key" name="api_key"
                               class="regular-text" value="<?php echo esc_attr($key); ?>" /></td>
                </tr>
                <!-- ... more rows ... -->
            </table>
        </div>
    </div>
</div>
```

```php [After — 12 lines with wp-fluent-admin]
use FluentAdmin\Components\{Page, Notice, Metabox, FormTable};

Page::make('My Plugin')->render(function () {
    if (isset($_GET['settings-updated'])) {
        echo Notice::make('Settings saved.', 'success')->dismissible();
    }

    echo Metabox::make('API Settings')->content(
        FormTable::make()
            ->text('api_key', 'API Key')
            ->select('environment', 'Environment', ['prod' => 'Production', 'dev' => 'Development'])
    );
});
```
:::

[Get Started →](/getting-started/installation)
[Browse Components →](/components/page)
```

### Getting Started — Installation (`getting-started/installation.md`)

```markdown
# Installation

## Requirements

- PHP 7.4 or higher
- WordPress 6.0 or higher
- Composer

## Install via Composer

\```bash
composer require wp-fluent-admin/wp-fluent-admin
\```

## Load the autoloader

In your plugin's main file, require Composer's autoloader:

\```php
require_once __DIR__ . '/vendor/autoload.php';
\```

That's it. No configuration, no service providers, no hooks to register.

## Avoiding conflicts with other plugins

If other plugins might also use wp-fluent-admin, use PHP-Scoper to
prefix the namespace. See the [PHP-Scoper guide](/extensibility/php-scoper).
```

### Getting Started — Quick Start (`getting-started/quick-start.md`)

Three progressively complex examples:

1. **Hello World** — A page with a notice and a button (5 lines).
2. **Settings Form** — A page with a metabox containing a form table that saves to `wp_options` (15 lines).
3. **Data Listing** — A page with tabs, one showing a ListTable with sortable columns and bulk actions (25 lines).

Each example shows the complete callback function that goes inside `add_menu_page()` — the developer can copy-paste it directly.

### Getting Started — Concepts (`getting-started/concepts.md`)

Short page explaining the four ideas a developer needs to understand:

1. **Components are echo-able** — They return HTML strings. `echo $component` works.
2. **Fluent chaining** — Every setter returns `$this`. Build complex UI in a single expression.
3. **Composable** — Components accept other components or closures as children.
4. **No CSS, no JS** — The library renders native WordPress admin markup. WordPress provides the styling.

No code longer than 6 lines in this page.

### Component Page Template

Every component page follows an identical structure. This consistency is critical — a developer who reads the Notice page knows exactly where to find the same information on the Tabs page.

```markdown
# {Component Name}

One sentence: what this component renders and when to use it.

## Basic Usage

\```php
echo Notice::make('Settings saved.', 'success');
\```

**Renders:**
\```html
<div class="notice notice-success"><p>Settings saved.</p></div>
\```

## Variants

### Dismissible
\```php
echo Notice::make('Done!', 'success')->dismissible();
\```

### Alt Style
\```php
echo Notice::make('Heads up.', 'warning')->alt();
\```

### All Types
\```php
echo Notice::make('Info message.', 'info');
echo Notice::make('Success!', 'success');
echo Notice::make('Be careful.', 'warning');
echo Notice::make('Something broke.', 'error');
\```

## API Reference

### Constructor

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$message` | `string` | — | The notice text (escaped automatically) |
| `$type` | `string` | `'info'` | One of: `info`, `success`, `warning`, `error`, `default` |
| `$dismissible` | `bool` | `false` | Whether to show the dismiss button |

### Methods

| Method | Returns | Description |
|--------|---------|-------------|
| `::make($message, $type, $dismissible)` | `static` | Factory constructor |
| `->dismissible(bool $value = true)` | `static` | Add/remove dismiss button |
| `->alt(bool $value = true)` | `static` | Use the alt background style |
| `->render()` | `string` | Return the HTML |

### Filters

| Filter | Arguments | Description |
|--------|-----------|-------------|
| `fluent_admin_notice_render` | `string $html, array $config` | Modify the rendered output |

## Cookbook

### Flash notice after settings save

\```php
add_action('admin_notices', function () {
    if (isset($_GET['settings-updated'])) {
        echo Notice::make('Settings saved.', 'success')->dismissible();
    }
});
\```

### Conditionally show notices

\```php
if ($api->hasError()) {
    echo Notice::make($api->getError(), 'error');
}
\```

## WordPress Reference

This component renders the native WordPress admin notice markup.
See the [WordPress Developer Resources](https://developer.wordpress.org/reference/hooks/admin_notices/)
for more on the underlying admin notice system.
```

### Field Page Template

Same structure as component pages, but the "Basic Usage" section shows the field both standalone and inside a FormTable:

```markdown
# Select Field

A dropdown select input.

## Inside a FormTable

\```php
FormTable::make()
    ->select('region', 'Region', [
        'us' => 'United States',
        'eu' => 'Europe',
        'ap' => 'Asia Pacific',
    ]);
\```

## Standalone

\```php
echo SelectField::make('region', 'Region')
    ->options(['us' => 'US', 'eu' => 'EU'])
    ->value('eu')
    ->description('Choose your deployment region.');
\```

**Renders:**
\```html
<select id="region" name="region">
    <option value="us">US</option>
    <option value="eu" selected>EU</option>
</select>
<p class="description">Choose your deployment region.</p>
\```
```

### Guide Page Template

Guides are full walkthroughs for common scenarios. They differ from component pages because they show how multiple components work together.

```markdown
# Building a Settings Page

A complete walkthrough for building a plugin settings page
with multiple sections, form saving, and validation feedback.

## What we're building

A settings page with:
- A success notice on save
- Two metaboxes: "General" and "API Connection"
- Text, select, and checkbox fields
- A save button that persists to `wp_options`

## Step 1: Register the admin page

\```php
add_action('admin_menu', function () {
    add_menu_page(
        'My Plugin Settings',
        'My Plugin',
        'manage_options',
        'my-plugin',
        'my_plugin_render_settings',
        'dashicons-admin-generic'
    );
});
\```

## Step 2: Build the UI

\```php
function my_plugin_render_settings() {
    Page::make('My Plugin Settings')->render(function () {
        // ... (complete working example)
    });
}
\```

## Step 3: Handle form submission

\```php
// ... register_setting() and sanitization
\```

## Complete code

The full plugin file, ready to copy into `wp-content/plugins/`.
```

### WordPress Markup Reference (`reference/wordpress-markup.md`)

A reference page mapping every component to the WordPress HTML/CSS it generates. This serves two purposes: (1) developers can verify the library outputs what they expect, and (2) it documents the WordPress admin markup patterns for anyone who wants to understand what's underneath.

```markdown
# WordPress Markup Reference

Every component in wp-fluent-admin renders standard WordPress admin HTML.
This page maps each component to its output markup.

## Notice

\```php
Notice::make('Hello', 'success')->dismissible()
\```

\```html
<div class="notice notice-success is-dismissible">
    <p>Hello</p>
</div>
\```

**WordPress CSS classes used:** `notice`, `notice-success`, `notice-info`,
`notice-warning`, `notice-error`, `notice-alt`, `is-dismissible`

## Button

...
```

---

## Documentation Quality Checklist

For each component/field page, verify:

- [ ] Opens with a one-sentence description
- [ ] Has a Basic Usage example that works when copy-pasted
- [ ] Shows the rendered HTML output
- [ ] Has a complete API Reference table (constructor params, all fluent methods, return types)
- [ ] Lists all available filters
- [ ] Includes at least one "Cookbook" recipe showing real-world usage
- [ ] Cross-links to related components (e.g. Button page links to ButtonGroup)
- [ ] No unexplained jargon — if a WordPress concept is referenced, link to the WP developer docs

For each guide page, verify:

- [ ] States what we're building upfront
- [ ] Provides a complete, working code example at the end
- [ ] Each step builds on the previous one
- [ ] Uses realistic data (not `foo`/`bar`)

---

## Deployment

- **Source:** `docs/` directory in the repo
- **Build:** VitePress builds to `docs/.vitepress/dist/`
- **Host:** GitHub Pages, deployed via GitHub Actions on push to `main`
- **Custom domain:** Optional, configure via CNAME file
- **Search:** VitePress built-in local search (no Algolia needed for v1)

### GitHub Actions workflow for docs

```yaml
name: Deploy Docs
on:
  push:
    branches: [main]
    paths: [docs/**]
jobs:
  deploy:
    runs-on: ubuntu-latest
    permissions:
      pages: write
      id-token: write
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 20
      - run: npm install
        working-directory: docs
      - run: npm run docs:build
        working-directory: docs
      - uses: actions/upload-pages-artifact@v3
        with:
          path: docs/.vitepress/dist
      - uses: actions/deploy-pages@v4
```

---

## Writing Style Guide

- **Voice:** Direct, second person ("you"), present tense.
- **Code first.** Never explain something that a code example can show. Put the example before the explanation.
- **No filler.** No "simply", "just", "easily", "of course". These words insult the reader if it isn't easy for them.
- **Method signatures in monospace.** Always write `->dismissible()` not "the dismissible method".
- **Consistent terminology.** "Component" not "element" or "widget". "Field" not "input" or "control". "Render" not "output" or "display".
- **Short paragraphs.** Maximum 3 sentences before a break or code block.
- **Working examples.** Every code example must be syntactically valid PHP. If it requires context (like being inside a function), show that context.
