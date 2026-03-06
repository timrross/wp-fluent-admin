# wp-fluent-admin

A fluent PHP component library for WordPress admin pages.  
Build native-looking admin UI without hand-coding HTML boilerplate.

:::code-group
```php [Before — 47 lines of boilerplate]
<div class="wrap">
    <h1>My Plugin</h1>
    <?php
    $updated = isset($_GET['settings-updated'])
        && 'true' === sanitize_text_field(wp_unslash($_GET['settings-updated']));
    if ($updated):
    ?>
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
    $updated = isset($_GET['settings-updated'])
        && 'true' === sanitize_text_field(wp_unslash($_GET['settings-updated']));

    if ($updated) {
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
