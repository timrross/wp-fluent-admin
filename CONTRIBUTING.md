# Contributing to wp-fluent-admin

## Local Setup

```bash
git clone https://github.com/wp-fluent-admin/wp-fluent-admin.git
cd wp-fluent-admin
composer install
composer test
```

Tests should pass before you start.

## Coding Standards

- **PHP 7.4+** — no `match`, `readonly`, `enum`, intersection types, named arguments, or fibers.
- **PSR-12** — run `composer lint` to check.
- **Strict types** — every PHP file must start with `declare(strict_types=1);`.
- **Escape all output** — use `Escape::html()`, `Escape::attr()`, `Escape::url()`. Never call WP escaping functions directly from component code.
- **No custom CSS or JS** — the library ships zero stylesheets or scripts.

## Testing Requirements

Every component and field must have a unit test that asserts the rendered HTML output.

- Unit tests go in `tests/Unit/` and must not require WordPress.
- Integration tests go in `tests/Integration/` with `@group integration` and a skip condition when WP is not loaded.
- Run tests: `composer test`
- Write the test in the same commit as the implementation.

## Commit Convention

Format: `type: short description`

Types: `feat`, `fix`, `test`, `docs`, `chore`, `refactor`

Examples:
- `feat: add ColorField component`
- `fix: escape label in CheckboxField`
- `test: add missing assertions to MetaboxTest`
- `docs: add Tabs component page`

One logical change per commit. Don't bundle unrelated work.

## Pull Request Process

1. Fork the repository and create a feature branch.
2. Write code and tests together.
3. Run `composer test` — all tests must pass.
4. Run `composer lint` — no PSR-12 violations.
5. Open a PR against `main` with a clear description of what changed and why.

New components require:
- Implementation in `src/Components/` or `src/Fields/`
- Unit test in `tests/Unit/`
- Documentation page in `docs/` following the template in `project/docs-strategy.md`
