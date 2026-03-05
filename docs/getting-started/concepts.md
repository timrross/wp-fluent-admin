# Core Concepts

## Components are echo-able

Every component returns an HTML string from `render()`, and `__toString()` proxies to it.

```php
echo \FluentAdmin\Components\Notice::make('Saved', 'success');
```

## Fluent chaining

Setters return `static`, so you can build full UI blocks in one expression.

```php
echo \FluentAdmin\Components\Button::make('Save')
    ->primary()
    ->submit();
```

## Composable children

Components can contain other components or callback output.

```php
echo \FluentAdmin\Components\Metabox::make('API')
    ->content(\FluentAdmin\Components\FormTable::make()->text('key', 'API Key'));
```

## No CSS, no JS shipped

`wp-fluent-admin` renders native wp-admin markup only. WordPress core styles those classes.  
You keep full control over data flow, routing, and persistence in your plugin code.
