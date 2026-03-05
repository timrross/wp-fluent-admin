# WordPress Markup Reference

Each component renders native wp-admin classes and structure.

## Page

```php
Page::make('Title')
```

```html
<div class="wrap"><h1>Title</h1>...</div>
```

Classes: `wrap`, `dashicons` (optional icon).

## Notice

```php
Notice::make('Saved', 'success')->dismissible()->alt()
```

```html
<div class="notice notice-success is-dismissible notice-alt"><p>Saved</p></div>
```

Classes: `notice`, `notice-info|success|warning|error`, `is-dismissible`, `notice-alt`.

## Button / Button Group

```html
<a class="button button-primary">...</a>
<div class="button-group">...</div>
```

Classes: `button`, `button-primary`, `button-small`, `button-hero`, `button-group`.

## Metabox / MetaboxContainer

```html
<div class="postbox"><div class="postbox-header"><h2 class="hndle">...</h2></div><div class="inside">...</div></div>
<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">...</div></div>
```

Classes/ids: `postbox`, `postbox-header`, `inside`, `hndle`, `metabox-holder`, `postbox-container`, `columns-{n}`.

## Tabs

```html
<h2 class="nav-tab-wrapper"><a class="nav-tab nav-tab-active">...</a></h2>
<div class="tab-content">...</div>
```

Classes: `nav-tab-wrapper`, `nav-tab`, `nav-tab-active`, `tab-content`.

## FormTable

```html
<table class="form-table" role="presentation"><tbody><tr><th scope="row">...</th><td>...</td></tr></tbody></table>
```

Classes: `form-table`, `description` (field help text).

## ListTable / DataTable

```html
<table class="wp-list-table widefat ...">...</table>
```

Classes: `wp-list-table`, `widefat`, `striped`, `narrow`.

## Spinner / Counter / Dashicon / Card

```html
<span class="spinner is-active"></span>
<span class="count">(3)</span>
<span class="update-plugins count-3"><span class="plugin-count">3</span></span>
<span class="dashicons dashicons-admin-generic"></span>
<div class="card"><h2 class="title">...</h2>...</div>
```

Classes: `spinner`, `is-active`, `count`, `update-plugins`, `plugin-count`, `dashicons`, `card`, `title`.
