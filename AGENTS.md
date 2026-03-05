# AGENTS.md — wp-fluent-admin

## Project Overview

**wp-fluent-admin** is a zero-dependency PHP library that provides a fluent, chainable API for building WordPress admin pages using native wp-admin markup. It ships no CSS, no JS, no opinions about data or routing. It outputs the same HTML that WordPress core uses.

The canonical design document is `project/wp-admin-ui-library-plan.md` in the repo root. The canonical documentation strategy is `project/docs-strategy.md` in the repo root. If anything in this file conflicts with those documents, the plan and strategy docs win.

---

## Standing Rules

These apply to every task in this project. Do not deviate.

### Namespace & Naming

- Root namespace: `FluentAdmin`
- Composer package name: `wp-fluent-admin/wp-fluent-admin`
- All classes live under `src/` with PSR-4 autoloading (`FluentAdmin\` → `src/`)
- No global functions. No procedural code outside bootstrap.
- WordPress filter prefix: `fluent_admin_` (e.g. `fluent_admin_notice_render`)

### PHP Standards

- PHP 7.4 minimum. Use typed properties, union types only where 7.4 supports them. No enums (8.1+), no readonly (8.1+), no intersection types (8.1+), no `match` (8.0+).
- Follow PSR-12 coding style.
- Every public method must have a `@return` docblock. Use `@param` for non-obvious parameters.
- Strict types declaration in every file: `declare(strict_types=1);`

### Security

- All output must be escaped. Use `esc_html()`, `esc_attr()`, `esc_url()` as appropriate.
- Raw HTML content requires the caller to explicitly use a `->rawContent()` or `->html()` setter — never auto-trust string input as HTML.
- Sanitise all `$_GET` / `$_POST` reads with `sanitize_text_field()` or appropriate WP sanitiser.
- Nonces: any component that renders a form action must support nonce generation and verification.

### Architecture Invariants

- **Output-only.** Components return HTML strings from `render()`. No side effects, no database queries, no option writes.
- **Fluent API.** Every setter returns `$this`. Every component has a `static make(...$args): static` factory.
- **Composable.** Components accept other components or closures as children.
- **`__toString()` calls `render()`.** Every component is echo-able.
- **WordPress filters.** `render()` passes output through `apply_filters("fluent_admin_{$component}_render", $html, $config)`.
- **No custom CSS or JS shipped.** The library relies entirely on wp-admin core styles.
- **WordPress functions may not exist in unit tests.** The `Support\Escape` class must provide fallback implementations when WP is not loaded (e.g. `esc_html()` → `htmlspecialchars()`). Integration tests run with WP loaded.

### Testing

- Framework: PHPUnit 9.x (for PHP 7.4 compat).
- Unit tests go in `tests/Unit/`, mirror `src/` structure: `tests/Unit/Components/NoticeTest.php` tests `src/Components/Notice.php`.
- Unit tests must NOT require WordPress. Stub or fallback any WP functions (see `Support\Escape`).
- Integration tests go in `tests/Integration/`. These require a WP environment (wp-env or similar). Not required for the initial build — flag with `@group integration`.
- Every component and every field must have a test that asserts the rendered HTML output contains the expected markup and classes.
- Run tests with: `composer test`

### File Creation

- One class per file. Filename matches class name exactly.
- No `index.php` files. No `bootstrap.php` outside the root.

### Git

- Conventional commits: `feat:`, `fix:`, `test:`, `docs:`, `chore:`.
- One logical change per commit. Don't bundle unrelated work.

---

## Directory Structure

```
wp-fluent-admin/
├── AGENTS.md
├── project/
|   ├── wp-admin-ui-library-plan.md
|   └── docs-strategy.md
├── composer.json
├── phpunit.xml
├── .gitignore
├── .github/
│   └── workflows/
│       └── ci.yml
├── src/
│   ├── Component.php
│   ├── Traits/
│   │   ├── Renderable.php
│   │   ├── HasAttributes.php
│   │   ├── HasChildren.php
│   │   └── Fluent.php
│   ├── Components/
│   │   ├── Page.php
│   │   ├── Notice.php
│   │   ├── Button.php
│   │   ├── ButtonGroup.php
│   │   ├── Metabox.php
│   │   ├── MetaboxContainer.php
│   │   ├── Tabs.php
│   │   ├── Tab.php
│   │   ├── FormTable.php
│   │   ├── ListTable.php
│   │   ├── DynamicListTable.php
│   │   ├── DataTable.php
│   │   ├── Spinner.php
│   │   ├── Counter.php
│   │   ├── Dashicon.php
│   │   └── Card.php
│   ├── Fields/
│   │   ├── Field.php
│   │   ├── TextField.php
│   │   ├── TextareaField.php
│   │   ├── SelectField.php
│   │   ├── CheckboxField.php
│   │   ├── RadioField.php
│   │   ├── PasswordField.php
│   │   ├── ColorField.php
│   │   └── MediaField.php
│   └── Support/
│       ├── Escape.php
│       ├── Attributes.php
│       └── Version.php
├── tests/
│   ├── bootstrap.php
│   ├── Unit/
│   │   ├── ComponentTest.php
│   │   ├── Components/
│   │   │   ├── NoticeTest.php
│   │   │   ├── ButtonTest.php
│   │   │   ├── ButtonGroupTest.php
│   │   │   ├── MetaboxTest.php
│   │   │   ├── PageTest.php
│   │   │   ├── TabsTest.php
│   │   │   ├── FormTableTest.php
│   │   │   ├── DataTableTest.php
│   │   │   ├── SpinnerTest.php
│   │   │   ├── CounterTest.php
│   │   │   ├── DashiconTest.php
│   │   │   └── CardTest.php
│   │   ├── Fields/
│   │   │   ├── TextFieldTest.php
│   │   │   ├── SelectFieldTest.php
│   │   │   ├── CheckboxFieldTest.php
│   │   │   └── RadioFieldTest.php
│   │   └── Support/
│   │       ├── EscapeTest.php
│   │       └── AttributesTest.php
│   └── Integration/
├── examples/
│   └── example-plugin/
│       ├── example-plugin.php
│       └── README.md
└── docs/
    ├── package.json
    ├── .vitepress/
    │   └── config.ts
    ├── index.md
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

## Build Checklist

Work through these in order. Each task is a single commit (or small group of commits). Mark complete with `[x]` when done.

### Phase 0 — Project Scaffolding

- [x] **0.1** Create `composer.json` with: name `wp-fluent-admin/wp-fluent-admin`, PSR-4 autoload `FluentAdmin\\` → `src/`, require-dev `phpunit/phpunit: ^9.6`, license `GPL-2.0-or-later`, minimum-stability `stable`.
- [x] **0.2** Create `phpunit.xml` targeting `tests/Unit` directory with bootstrap `tests/bootstrap.php`.
- [x] **0.3** Create `tests/bootstrap.php`. It must: require Composer autoload, define stub WP functions (`esc_html`, `esc_attr`, `esc_url`, `esc_textarea`, `sanitize_text_field`, `sanitize_title`, `wp_kses`, `wp_kses_post`, `apply_filters`, `add_query_arg`, `absint`, `__`, `esc_sql`) if they don't already exist. Stub `esc_html` as `htmlspecialchars($s, ENT_QUOTES, 'UTF-8')`. Stub `esc_attr` identically. Stub `apply_filters` to return the first non-tag argument. Stub `__` to return its first argument.
- [x] **0.4** Create `.gitignore` ignoring `vendor/`, `.phpunit.result.cache`, `composer.lock`.
- [x] **0.5** Create `.github/workflows/ci.yml`: matrix PHP 7.4, 8.0, 8.1, 8.2, 8.3. Steps: checkout, setup-php, `composer install`, `composer test`.
- [x] **0.6** Add `scripts` section to `composer.json`: `"test": "phpunit"`, `"test:coverage": "phpunit --coverage-text"`.
- [x] **0.7** Run `composer install` and `composer test` to verify empty test suite passes. Commit: `chore: scaffold project`.

### Phase 1 — Base Classes & Traits

- [x] **1.1** Create `src/Support/Escape.php`. Static methods: `html(string): string`, `attr(string): string`, `url(string): string`, `textarea(string): string`. Each calls the WP equivalent if it exists, otherwise falls back to `htmlspecialchars`. All methods must be used by components instead of calling WP functions directly.
- [x] **1.2** Create `src/Support/Attributes.php`. Takes an associative array of HTML attributes, returns a rendered attribute string. Must handle: boolean attributes (e.g. `disabled` → `disabled`), class arrays merged into a single string, data-* attributes. All values escaped through `Escape::attr()`.
- [x] **1.3** Create `src/Traits/Renderable.php`. Provides `render(): string` (calls abstract `html()`, wraps in `apply_filters`), `__toString(): string`, `toHtml(): string` (alias of render).
- [x] **1.4** Create `src/Traits/HasAttributes.php`. Provides `id(string): static`, `addClass(string): static`, `removeClass(string): static`, `attr(string, mixed): static`, `data(string, mixed): static`. Stores in `$this->attributes` array.
- [x] **1.5** Create `src/Traits/HasChildren.php`. Provides `child(Component|string|callable): static`, `children(array): static`, `renderChildren(): string`. Callables are invoked with `ob_start()`/`ob_get_clean()`.
- [x] **1.6** Create `src/Component.php`. Abstract class using `Renderable` and `HasAttributes` traits. Implements: `static make(...$args): static`, `abstract protected html(): string`, `__call(string, array): static` for generic fluent setters, `protected array $config`.
- [x] **1.7** Write tests: `tests/Unit/Support/EscapeTest.php` (test fallback escaping), `tests/Unit/Support/AttributesTest.php` (test rendering with booleans, classes, data attrs), `tests/Unit/ComponentTest.php` (test a concrete anonymous subclass for make(), __toString, __call, render filter).
- [x] **1.8** Run tests, all green. Commit: `feat: base component class and support utilities`.

### Phase 2 — Tier 1 Components (Core Layout)

- [x] **2.1** Create `src/Components/Notice.php`. Constructor: `(string $message, string $type = 'info', bool $dismissible = false)`. Fluent: `->dismissible(bool)`, `->alt(bool)`. Types: `info`, `success`, `warning`, `error`, `default`. Output: `<div class="notice notice-{type} [is-dismissible] [notice-alt]"><p>{escaped message}</p></div>`. Filter: `fluent_admin_notice_render`.
- [x] **2.2** Write `tests/Unit/Components/NoticeTest.php`. Assert: correct classes for each type, dismissible adds `is-dismissible`, alt adds `notice-alt`, message is escaped, default type has no `notice-` suffix, XSS in message is escaped.
- [x] **2.3** Create `src/Components/Button.php`. Constructor: `(string $text, string $url = '#')`. Fluent: `->primary()`, `->secondary()`, `->small()`, `->hero()`, `->submit()` (renders `<button type="submit">` instead of `<a>`), `->disabled()`, `->newTab()`. Output: `<a href="{url}" class="button [button-primary] [button-small] [button-hero]">{text}</a>` or `<button>` variant. Filter: `fluent_admin_button_render`.
- [x] **2.4** Write `tests/Unit/Components/ButtonTest.php`. Assert: default renders secondary button, primary adds class, hero adds class, small adds class, submit renders `<button>` element, disabled adds `disabled` attribute, URL is escaped.
- [x] **2.5** Create `src/Components/ButtonGroup.php`. Fluent: `->add(Button ...$buttons)`, accepts Button instances or uses `HasChildren`. Output: `<div class="button-group">{children}</div>`. Filter: `fluent_admin_buttongroup_render`.
- [x] **2.6** Write `tests/Unit/Components/ButtonGroupTest.php`. Assert: wraps children in `button-group` div.
- [x] **2.7** Create `src/Components/Metabox.php`. Constructor: `(string $title)`. Fluent: `->content(string|Component|callable)`, `->id(string)`, `->closed()`. Output: `<div class="postbox [closed]" id="{id}"><div class="postbox-header"><h2>{title}</h2></div><div class="inside">{content}</div></div>`. Callable content is captured with ob_start. Filter: `fluent_admin_metabox_render`.
- [x] **2.8** Write `tests/Unit/Components/MetaboxTest.php`. Assert: title is escaped, content renders inside `.inside`, callable content is captured, closed class when set, id attribute set.
- [x] **2.9** Create `src/Components/MetaboxContainer.php`. Fluent: `->columns(int $count = 2)`, `->primary(Component|callable)`, `->sidebar(Component|callable)`. Output: `<div id="poststuff"><div id="post-body" class="metabox-holder columns-{n}"><div id="post-body-content">{primary}</div><div id="postbox-container-1" class="postbox-container">{sidebar}</div></div></div>`. Filter: `fluent_admin_metaboxcontainer_render`.
- [x] **2.10** Create `src/Components/Page.php`. Constructor: `(string $title)`. Fluent: `->content(callable)`, `->icon(string $dashicon)`. Output: `<div class="wrap"><h1>[icon]{title}</h1>{content}</div>`. Callable is captured with ob_start. Filter: `fluent_admin_page_render`.
- [x] **2.11** Write `tests/Unit/Components/PageTest.php`. Assert: wraps in `.wrap`, title in `<h1>`, title is escaped, icon renders dashicon span before title, content callable captured.
- [x] **2.12** Run full test suite, all green. Commit: `feat: tier 1 components — Page, Notice, Button, ButtonGroup, Metabox, MetaboxContainer`.

### Phase 3 — Form Fields

- [x] **3.1** Create `src/Fields/Field.php`. Abstract class extending `Component`. Constructor: `(string $name, string $label = '')`. Properties: `$name`, `$label`, `$value`, `$description`, `$attributes`. Fluent: `->value(mixed)`, `->description(string)`, `->placeholder(string)`, `->required()`, `->disabled()`, `->name()` getter, `->id()` getter (defaults to name). Abstract: `protected inputHtml(): string`. `html()` returns just the input — FormTable handles the `<tr>` wrapping.
- [x] **3.2** Create `src/Fields/TextField.php`. Extends Field. `inputHtml()` returns `<input type="text" id="{id}" name="{name}" value="{value}" class="regular-text" {attributes} />`. Supports `->size('small'|'regular'|'large')` mapping to `small-text`, `regular-text`, `large-text`.
- [x] **3.3** Create `src/Fields/TextareaField.php`. `inputHtml()` returns `<textarea id="{id}" name="{name}" class="large-text" rows="{rows}">{value}</textarea>`. Fluent: `->rows(int)` (default 5).
- [x] **3.4** Create `src/Fields/SelectField.php`. Constructor adds `array $options`. `inputHtml()` returns `<select id="{id}" name="{name}">{options}</select>`. Options rendered as `<option value="{key}" [selected]>{label}</option>`.
- [x] **3.5** Create `src/Fields/CheckboxField.php`. `inputHtml()` returns `<label><input type="checkbox" id="{id}" name="{name}" value="1" [checked] /> {label text}</label>`. Fluent: `->checked(bool)`.
- [x] **3.6** Create `src/Fields/RadioField.php`. Constructor adds `array $options`. `inputHtml()` returns `<fieldset>` with `<label><input type="radio" name="{name}" value="{key}" [checked] /> {label}</label><br>` for each option.
- [x] **3.7** Create `src/Fields/PasswordField.php`. Same as TextField but `type="password"` and always `regular-text` class.
- [x] **3.8** Write `tests/Unit/Fields/TextFieldTest.php`. Assert: renders input with correct type, name, id, value, placeholder, class, disabled attribute, required attribute, description paragraph.
- [x] **3.9** Write `tests/Unit/Fields/SelectFieldTest.php`. Assert: renders select with options, selected option marked, escaped values.
- [x] **3.10** Write `tests/Unit/Fields/CheckboxFieldTest.php`. Assert: renders checkbox input, label wraps input, checked attribute when set.
- [x] **3.11** Write `tests/Unit/Fields/RadioFieldTest.php`. Assert: renders fieldset, each option as radio input, correct name grouping, checked state.
- [x] **3.12** Run tests, all green. Commit: `feat: form field components — text, textarea, select, checkbox, radio, password`.

### Phase 4 — Tier 2 Components (Data Display)

- [x] **4.1** Create `src/Components/FormTable.php`. Uses `HasChildren`. Fluent shortcut methods: `->text(name, label, options)`, `->password(name, label, options)`, `->textarea(name, label, options)`, `->select(name, label, choices, options)`, `->checkbox(name, label, options)`, `->radio(name, label, choices, options)`, `->field(Field)`. Each shortcut creates the appropriate Field and appends it. `html()` renders: `<table class="form-table" role="presentation"><tbody>` then for each field: `<tr><th scope="row"><label for="{id}">{label}</label></th><td>{field->render()}{description}</td></tr>` then `</tbody></table>`. Filter: `fluent_admin_formtable_render`.
- [x] **4.2** Write `tests/Unit/Components/FormTableTest.php`. Assert: renders `table.form-table`, each field gets a `<tr>`, labels have correct `for` attribute, description renders as `<p class="description">`, shortcut methods produce correct field types.
- [x] **4.3** Create `src/Components/DataTable.php`. Constructor: `(array $headers)`. Fluent: `->rows(array $rows)`, `->striped(bool)`, `->narrow(bool)`. `html()` renders: `<table class="wp-list-table widefat [striped] [narrow]"><thead><tr>{th cells}</tr></thead><tbody>{tr rows}</tbody></table>`. Each row is an associative array keyed by header keys. Filter: `fluent_admin_datatable_render`.
- [x] **4.4** Write `tests/Unit/Components/DataTableTest.php`. Assert: renders correct table classes, header cells, body rows, striped class, all cell content escaped.
- [x] **4.5** Create `src/Components/Spinner.php`. Constructor: `(bool $active = true)`. `html()` returns `<span class="spinner [is-active]"></span>`. Filter: `fluent_admin_spinner_render`.
- [x] **4.6** Create `src/Components/Counter.php`. Constructor: `(int $count)`. Fluent: `->menuStyle()` for the update-plugins bubble variant. `html()` returns either `<span class="count">({count})</span>` or `<span class="update-plugins count-{n}"><span class="plugin-count">{n}</span></span>`. Filter: `fluent_admin_counter_render`.
- [x] **4.7** Write `tests/Unit/Components/SpinnerTest.php` and `tests/Unit/Components/CounterTest.php`.
- [x] **4.8** Create `src/Components/DynamicListTable.php`. Extends `\WP_List_Table` (or no-ops if class doesn't exist in unit tests). Config-driven: accepts array with keys `columns`, `sortable`, `bulk`, `per_page`, `data_cb`, `count_cb`. Implements: `get_columns()`, `get_sortable_columns()`, `get_bulk_actions()`, `column_default()`, `column_cb()`, `prepare_items()`. See plan for full implementation. `column_default` escapes all output. `prepare_items` calls `data_cb` with `['per_page' => int, 'page' => int, 'orderby' => string, 'order' => string]` and `count_cb` with no args.
- [x] **4.9** Create `src/Components/ListTable.php`. Extends Component. Fluent: `->columns(array)`, `->sortable(array)`, `->bulkActions(array)`, `->perPage(int)`, `->data(callable)`, `->count(callable)`, `->rowActions(callable)` (receives row item, returns array of action links). `html()` instantiates `DynamicListTable`, calls `prepare_items()`, captures `display()` output with ob_start. Include a `->search(bool)` option that renders the search box. Filter: `fluent_admin_listtable_render`.
- [x] **4.10** ListTable cannot be meaningfully unit tested without WP_List_Table. Create `tests/Integration/Components/ListTableTest.php` with `@group integration` annotation and a skip condition if WP is not loaded. The test should: create a ListTable with dummy data callback, render it, assert output contains `wp-list-table`.
- [x] **4.11** Run unit tests (excluding integration group), all green. Commit: `feat: tier 2 components — FormTable, DataTable, ListTable, Spinner, Counter`.

### Phase 5 — Tier 3 Components (Navigation & Feedback)

- [x] **5.1** Create `src/Components/Tabs.php`. Fluent: `->tab(string $label, callable|string|Component $content)`, `->active(string $label)`. Determines active tab from `$_GET['tab']` falling back to first tab. Renders `<h2 class="nav-tab-wrapper">` with `<a class="nav-tab [nav-tab-active]">` for each tab, URL built with `add_query_arg('tab', $slug)`. Active tab's content rendered below. Slugs generated via `sanitize_title()`. Filter: `fluent_admin_tabs_render`.
- [x] **5.2** Write `tests/Unit/Components/TabsTest.php`. Assert: renders nav-tab-wrapper, each tab has nav-tab class, first tab active by default, active tab content rendered, inactive tab content not rendered, slugs are sanitised.
- [x] **5.3** Create `src/Components/Dashicon.php`. Constructor: `(string $icon)`. `html()` returns `<span class="dashicons dashicons-{icon}"></span>`. If `$icon` already starts with `dashicons-`, don't double-prefix. Filter: `fluent_admin_dashicon_render`.
- [x] **5.4** Create `src/Components/Card.php`. Constructor: `(string $title = '')`. Fluent: `->content(string|Component|callable)`, `->footer(string|Component|callable)`. Output: `<div class="card"><h2 class="title">{title}</h2>{content}{footer}</div>`. If no title, skip `<h2>`. Filter: `fluent_admin_card_render`.
- [x] **5.5** Write `tests/Unit/Components/DashiconTest.php` and `tests/Unit/Components/CardTest.php`.
- [x] **5.6** Run full unit test suite, all green. Commit: `feat: tier 3 components — Tabs, Dashicon, Card`.

### Phase 6 — Advanced Fields

- [x] **6.1** Create `src/Fields/ColorField.php`. `inputHtml()` renders `<input type="text" class="wp-color-picker" />`. Also outputs a script call to `jQuery(el).wpColorPicker()` wrapped in a `DOMContentLoaded` listener — or provides a `->enqueue()` method that calls `wp_enqueue_script('wp-color-picker')` and `wp_enqueue_style('wp-color-picker')`. Since the library is output-only, prefer the enqueue approach: document that the caller must call `$field->enqueue()` during `admin_enqueue_scripts`.
- [x] **6.2** Create `src/Fields/MediaField.php`. `inputHtml()` renders a hidden input for the attachment ID, an `<img>` preview, and a "Select Image" / "Remove" button. Requires `wp_enqueue_media()`. Provide a static `::enqueueAssets()` method the caller hooks into `admin_enqueue_scripts`. The JS for opening the media modal should be minimal inline script or documented as a requirement.
- [x] **6.3** Write tests for ColorField and MediaField (assert HTML structure; JS/enqueue behaviour is documented, not tested in unit).
- [ ] **6.4** Commit: `feat: advanced fields — ColorField, MediaField`.

### Phase 7 — Example Plugin

- [x] **7.1** Create `examples/example-plugin/example-plugin.php`. Standard WordPress plugin header. Registers an admin menu page using `add_menu_page()`. In the callback, uses wp-fluent-admin to build a full page demonstrating: Page with icon, Notice (all types), Tabs (3 tabs), Metabox with FormTable (text, select, checkbox, textarea, password, radio), ButtonGroup, DataTable with sample data, Card, Counter, Spinner, Dashicon.
- [x] **7.2** Create `examples/example-plugin/README.md` explaining how to install: symlink or copy into `wp-content/plugins/`, run `composer install` in the library root, activate.
- [ ] **7.3** Commit: `docs: example plugin demonstrating all components`.

### Phase 8 — README & Repo Docs

- [x] **8.1** Write `README.md`. Sections: hero (before/after code comparison — see `docs-strategy.md` landing page template), installation (composer require, autoloader), quick start (3 examples: simple page, settings form, list table), component reference (table of all components with one-liner), extensibility (custom components, custom fields, filters), compatibility (PHP/WP versions), contributing (link to CONTRIBUTING.md), license. The README is the first thing people see on GitHub — it must sell the library in the first scroll.
- [x] **8.2** Write `CONTRIBUTING.md`. Sections: local setup (composer install, composer test), coding standards (PSR-12, strict types), testing requirements (every component needs tests, unit vs integration), commit convention, PR process.
- [x] **8.3** Write `LICENSE` file. GPL-2.0-or-later (WordPress compatible).
- [x] **8.4** Commit: `docs: README, CONTRIBUTING, LICENSE`.

### Phase 9 — Documentation Site Scaffold

The canonical documentation strategy is in `docs-strategy.md`. Follow it precisely for structure, page templates, and writing style.

- [x] **9.1** Create `docs/` directory. Initialise VitePress: `docs/package.json` with `vitepress` dependency, scripts `docs:dev` and `docs:build`. Create `docs/.vitepress/config.ts` with: site title `wp-fluent-admin`, description, nav bar (Getting Started, Components, Fields, Guides, Extensibility, Reference), sidebar config matching the structure in `docs-strategy.md`, built-in local search enabled.
- [x] **9.2** Create `docs/index.md` — the hero landing page. Must contain the before/after code comparison (raw WordPress boilerplate vs wp-fluent-admin), a "Get Started" link, and a "Browse Components" link. Follow the landing page template in `docs-strategy.md` exactly.
- [x] **9.3** Commit: `docs: VitePress scaffold and landing page`.

### Phase 10 — Getting Started Docs

- [x] **10.1** Create `docs/getting-started/installation.md`. Cover: requirements (PHP 7.4+, WP 6.0+, Composer), `composer require`, loading the autoloader in a plugin, link to PHP-Scoper guide for conflict avoidance.
- [x] **10.2** Create `docs/getting-started/quick-start.md`. Three progressive examples: (1) Hello World — Page + Notice + Button in 5 lines, (2) Settings Form — Page + Metabox + FormTable saving to wp_options in 15 lines, (3) Data Listing — Page + Tabs + ListTable with sortable columns in 25 lines. Each example is a complete `add_menu_page` callback — copy-pasteable.
- [x] **10.3** Create `docs/getting-started/concepts.md`. Four concepts in under 200 words total: components are echo-able, fluent chaining, composable children, no CSS/JS shipped. No code block longer than 6 lines.
- [x] **10.4** Commit: `docs: getting started — installation, quick start, concepts`.

### Phase 11 — Component Documentation

Every component page follows the template in `docs-strategy.md`: one-sentence description → Basic Usage with code + rendered HTML → Variants → API Reference table (constructor, methods, filters) → Cookbook recipe → WordPress Reference link.

- [x] **11.1** Create `docs/components/page.md`, `docs/components/notice.md`, `docs/components/button.md`, `docs/components/button-group.md`. Each follows the component page template. Cross-link related components (Button ↔ ButtonGroup).
- [x] **11.2** Create `docs/components/metabox.md`, `docs/components/metabox-container.md`. Show single metabox, collapsible metabox, two-column layout with sidebar.
- [x] **11.3** Create `docs/components/tabs.md`, `docs/components/form-table.md`. Tabs page must show URL-based state persistence. FormTable page must show all shortcut methods with examples.
- [x] **11.4** Create `docs/components/list-table.md`, `docs/components/data-table.md`. ListTable page must show: basic usage, sortable columns, bulk actions, search box, row actions, custom column rendering. DataTable shows the simpler static alternative.
- [x] **11.5** Create `docs/components/spinner.md`, `docs/components/counter.md`, `docs/components/dashicon.md`, `docs/components/card.md`. These are shorter pages — basic usage + API reference is sufficient.
- [x] **11.6** Commit: `docs: all component pages`.

### Phase 12 — Field Documentation

Every field page follows the field template in `docs-strategy.md`: show inside FormTable first, then standalone, then rendered HTML, then API reference.

- [x] **12.1** Create `docs/fields/overview.md`. Explains the Field base class, how fields work standalone vs inside FormTable, and lists all available fields with one-liner descriptions.
- [x] **12.2** Create `docs/fields/text.md`, `docs/fields/textarea.md`, `docs/fields/password.md`. Text page covers size variants (`small-text`, `regular-text`, `large-text`).
- [x] **12.3** Create `docs/fields/select.md`, `docs/fields/checkbox.md`, `docs/fields/radio.md`. Select page covers option groups if supported. Checkbox covers checked state. Radio covers option arrays.
- [x] **12.4** Create `docs/fields/color.md`, `docs/fields/media.md`. Both must document the `enqueue()` requirement clearly — these are the only fields that need JS.
- [x] **12.5** Commit: `docs: all field pages`.

### Phase 13 — Guides

Guides show multiple components working together in real-world scenarios. Each guide produces a complete, working plugin file.

- [x] **13.1** Create `docs/guides/settings-page.md`. Walk through building a complete settings page: `register_setting()`, `add_menu_page()`, Page + Notice + Metabox + FormTable + Button, form submission handling. Complete code at the end.
- [x] **13.2** Create `docs/guides/list-page.md`. Walk through building a data listing page: custom table in `$wpdb`, ListTable with columns/sorting/bulk actions/search, delete handling with nonces. Complete code at the end.
- [x] **13.3** Create `docs/guides/dashboard-page.md`. Walk through building a dashboard: MetaboxContainer with two-column layout, Cards with stats, DataTable with recent items, ButtonGroup for quick actions.
- [x] **13.4** Create `docs/guides/multi-tab-settings.md`. Walk through building a multi-section settings page: Tabs with 3+ tabs, each containing different FormTable configurations, shared save handler.
- [x] **13.5** Create `docs/guides/custom-components.md`. Walk through creating a custom component by extending the base class. Build a "StatusCard" component from scratch. Show how to register custom filters.
- [x] **13.6** Commit: `docs: all guide pages`.

### Phase 14 — Extensibility & Reference Docs

- [x] **14.1** Create `docs/extensibility/custom-components.md`. How to extend `Component`, implement `html()`, use traits, register with the filter system. Complete working example.
- [x] **14.2** Create `docs/extensibility/custom-fields.md`. How to extend `Field`, implement `inputHtml()`, integrate with FormTable. Complete working example (e.g. a CodeEditorField).
- [x] **14.3** Create `docs/extensibility/filters.md`. Complete list of every filter the library fires, with the filter name, arguments, and a usage example for each. Organised by component.
- [x] **14.4** Create `docs/extensibility/php-scoper.md`. Step-by-step guide: install php-scoper, configure, run, update autoload, verify. Show the before/after namespace.
- [x] **14.5** Create `docs/reference/component-base.md`. Full API reference for the abstract `Component` class and all traits (`Renderable`, `HasAttributes`, `HasChildren`). Every method, every parameter.
- [x] **14.6** Create `docs/reference/support-classes.md`. API reference for `Escape`, `Attributes`, `Version`.
- [x] **14.7** Create `docs/reference/wordpress-markup.md`. Map of every component to its rendered HTML and WordPress CSS classes used. This page serves as both a verification reference and a WordPress admin markup cheatsheet.
- [x] **14.8** Create `docs/changelog.md`. Stub with v0.1.0 entry.
- [x] **14.9** Commit: `docs: extensibility, reference, and changelog pages`.

### Phase 15 — Docs Deployment & CI

- [x] **15.1** Create `.github/workflows/docs.yml`. Triggers on push to `main` when `docs/**` changes. Steps: checkout, setup Node 20, `npm install` in `docs/`, `npm run docs:build`, deploy to GitHub Pages using `actions/upload-pages-artifact` and `actions/deploy-pages`.
- [x] **15.2** Add `docs/node_modules/` and `docs/.vitepress/cache/` and `docs/.vitepress/dist/` to `.gitignore`.
- [x] **15.3** Verify local `npm run docs:dev` serves the site and all navigation/links work. Every sidebar link resolves. Every code example is syntactically valid PHP (spot-check at minimum).
- [x] **15.4** Commit: `ci: docs deployment to GitHub Pages`.

### Phase 16 — PHP-Scoper Configuration

- [x] **16.1** Add `humbug/php-scoper` as a dev dependency. Create `scoper.inc.php` config that: prefixes the `FluentAdmin` namespace with a configurable vendor prefix (default `Isolated\FluentAdmin`), excludes WordPress functions and classes from prefixing, outputs to `build/`.
- [x] **16.2** Add composer script: `"scope": "php-scoper add-prefix --output-dir=build/"`.
- [x] **16.3** Test that scoped build still passes unit tests (adjust autoload path temporarily).
- [x] **16.4** Commit: `feat: php-scoper configuration for conflict-free inclusion`.

### Phase 17 — CI & Release Prep

- [x] **17.1** Verify CI workflow runs on push and PR to `main`. Matrix: PHP 7.4, 8.0, 8.1, 8.2, 8.3.
- [x] **17.2** Add a `phpcs` step to CI using `squizlabs/php_codesniffer` with PSR-12 ruleset. Add composer script `"lint": "phpcs src/ tests/ --standard=PSR12"`. Fix any violations.
- [x] **17.3** Tag `v0.1.0`. Update `composer.json` with Packagist metadata: description, keywords (`wordpress`, `admin`, `ui`, `fluent`, `component`), authors, homepage (GitHub URL).
- [x] **17.4** Commit: `chore: CI finalisation and v0.1.0 release prep`.

---

## API Disambiguation

The plan document (`wp-admin-ui-library-plan.md`) contains illustrative examples that sometimes use slightly different method names or patterns. Where ambiguity exists, this section is the canonical answer. Claude Code: read this section before implementing any component.

### Page component

- Constructor: `Page::make(string $title)`
- Content method: `->content(callable $callback)` — the callable receives no arguments. Inside the callable, echo components directly.
- Alternative render pattern: `->render(callable $callback)` is an alias of `->content()`. Implement `content()` as the primary method; add `render(callable)` as a convenience alias that sets content and returns the HTML string in one call. When `render()` is called with no arguments, it returns the HTML (standard Component behaviour). When `render(callable)` is called with a callable, it sets the content and immediately echoes the result (for use as the top-level page callback).

```php
// Both of these must work:
Page::make('Title')->content(function () { echo 'hi'; })->render();  // returns string
Page::make('Title')->render(function () { echo 'hi'; });            // echoes directly
```

### FormTable shortcut methods — parameter mapping

Each shortcut creates the corresponding Field, passes options, and appends it:

```php
// ->text(string $name, string $label, array $options = [])
// Creates TextField::make($name, $label), then applies $options:
//   'placeholder' => ->placeholder(string)
//   'size'        => ->size(string)      // 'small', 'regular', 'large'
//   'description' => ->description(string)
//   'value'       => ->value(mixed)
//   'required'    => ->required()
//   'disabled'    => ->disabled()

// ->select(string $name, string $label, array $choices, array $options = [])
// Creates SelectField::make($name, $label, $choices), then applies $options as above.

// ->checkbox(string $name, string $label, array $options = [])
// Creates CheckboxField::make($name, $label), then applies $options.
//   'checked'     => ->checked(bool)

// ->radio(string $name, string $label, array $choices, array $options = [])
// Creates RadioField::make($name, $label, $choices), then applies $options.

// ->textarea(string $name, string $label, array $options = [])
//   'rows'        => ->rows(int)

// ->password(string $name, string $label, array $options = [])
// Same as text options minus 'size'.

// ->field(Field $field)
// Appends any pre-configured Field instance directly.
```

### Field html() vs inputHtml()

- `html()` (from Component) calls `inputHtml()` and appends the description paragraph if set. This is what gets called during render.
- `inputHtml()` (abstract, implemented by each field) returns just the form control element.
- When a Field is rendered inside a FormTable, the FormTable wraps it in `<tr><th><label>...</th><td>{field->render()}</td></tr>`.
- When a Field is rendered standalone (e.g. `echo TextField::make('name', 'Name')`), it renders just the input + description, no table row.

### ListTable — handling missing WP_List_Table in tests

`DynamicListTable` must be conditionally defined:

```php
// At the top of src/Components/DynamicListTable.php:
if (!class_exists('WP_List_Table')) {
    // Don't define the class at all. ListTable::html() should check
    // class_exists('WP_List_Table') and return a fallback message:
    // '<p>WP_List_Table is not available.</p>'
    return;
}
```

This means `DynamicListTable.php` uses a conditional class definition. The `ListTable` component checks `class_exists('FluentAdmin\Components\DynamicListTable')` before attempting to instantiate it. In unit tests, `DynamicListTable` won't exist, so `ListTable::html()` returns the fallback string. In integration tests with WP loaded, it works normally.

### Content methods — string vs Component vs callable

Several components accept content as `string|Component|callable`. The resolution order is:

1. If `callable` — capture with `ob_start()` / `ob_get_clean()`.
2. If `Component` instance — call `->render()` on it.
3. If `string` — escape with `Escape::html()` unless the method is `->rawContent()` / `->htmlContent()`.

Implement this in a shared helper, either in `HasChildren` trait or as a static method on `Component`:

```php
protected function resolveContent($content): string
{
    if (is_callable($content)) {
        ob_start();
        $content();
        return ob_get_clean();
    }
    if ($content instanceof Component) {
        return $content->render();
    }
    return Escape::html((string) $content);
}
```

---

## Task Execution Notes

When working on any task above:

1. **Read the plan first.** Check `wp-admin-ui-library-plan.md` for the detailed API signatures and HTML output for that component. The plan has working code examples — follow the API shapes defined there.
2. **Read the API Disambiguation section above.** If the plan contradicts this section, this section wins.
3. **Write the test first** (or immediately after the implementation, same commit). Don't batch tests into a separate phase.
4. **Run `composer test` after every task.** Never commit with failing tests.
5. **One component = one commit** unless two components are tightly coupled (e.g. Button + ButtonGroup).
6. **Don't gold-plate.** If a component works and tests pass, move on. Polish comes in later iterations.
7. **ListTable is special.** It's the only component that wraps a WP core class directly. Unit tests can only verify the fluent API configuration; the actual rendering is integration-only. That's fine. See API Disambiguation for the conditional class pattern.
8. **FormTable shortcut methods** (`.text()`, `.select()`, etc.) are convenience wrappers. The underlying Field classes must also work standalone via `Field::make()`. See API Disambiguation for the exact parameter mapping.

### Documentation-specific notes

9. **Read `docs-strategy.md` before writing any docs.** It contains page templates, writing style rules, and the quality checklist. Follow the templates exactly — consistency across pages is more important than any single page being clever.
10. **Code examples must be valid PHP.** Every snippet in the docs should be syntactically correct and runnable in context. If a snippet requires surrounding code (like being inside a function), show that context.
11. **Lead with code, follow with explanation.** The developer should see the code example before reading why it works. Never write a paragraph of explanation before showing the code.
12. **No filler words in docs.** Never use "simply", "just", "easily", "of course", "obviously". If something were obvious, the reader wouldn't be reading the docs.
13. **Cross-link aggressively.** Every component page should link to related components. Every field page should link to FormTable. Every guide should link to the components it uses.
14. **The README is marketing. The docs site is reference.** The README should make someone want to use the library (before/after comparison, quick install, "look how clean this is"). The docs site should make them successful at using it (complete API, working examples, edge cases).
15. **Docs must reflect the actual implementation.** Write component docs after implementing the component. The API Reference table on each docs page must match the actual public methods on the class. If you implemented a method differently than the checklist specified (because of a practical issue), update the docs to match reality, not the checklist.
