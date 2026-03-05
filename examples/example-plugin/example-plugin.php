<?php

/**
 * Plugin Name: WP Fluent Admin — Example Plugin
 * Description: Demonstrates all components in the wp-fluent-admin library.
 * Version:     0.1.0
 * Requires PHP: 7.4
 * Author:      wp-fluent-admin
 * License:     GPL-2.0-or-later
 */

declare(strict_types=1);

// Require the library autoloader (path assumes plugin is in wp-content/plugins/).
$autoload = __DIR__ . '/../../vendor/autoload.php';
if (!file_exists($autoload)) {
    // Fallback: installed as a standalone plugin alongside the library
    $autoload = __DIR__ . '/vendor/autoload.php';
}
require_once $autoload;

use FluentAdmin\Components\Button;
use FluentAdmin\Components\ButtonGroup;
use FluentAdmin\Components\Card;
use FluentAdmin\Components\Counter;
use FluentAdmin\Components\Dashicon;
use FluentAdmin\Components\DataTable;
use FluentAdmin\Components\FormTable;
use FluentAdmin\Components\Metabox;
use FluentAdmin\Components\Notice;
use FluentAdmin\Components\Page;
use FluentAdmin\Components\Spinner;
use FluentAdmin\Components\Tabs;

add_action('admin_menu', function (): void {
    add_menu_page(
        'WP Fluent Admin Example',
        'Fluent Admin',
        'manage_options',
        'fluent-admin-example',
        'fluent_admin_example_render_page',
        'dashicons-admin-generic',
        99
    );
});

function fluent_admin_example_render_page(): void
{
    Page::make('WP Fluent Admin — Component Showcase')
        ->icon('dashicons-admin-generic')
        ->render(function (): void {

            // --- Notices ---
            echo Notice::make('This is an info notice.', 'info');
            echo Notice::make('Settings saved successfully.', 'success')->dismissible();
            echo Notice::make('Check your configuration.', 'warning')->alt();
            echo Notice::make('Something went wrong.', 'error');

            // --- Tabs ---
            echo Tabs::make()
                ->tab('Components', function (): void {
                    fluent_admin_example_components_tab();
                })
                ->tab('Form Fields', function (): void {
                    fluent_admin_example_fields_tab();
                })
                ->tab('Data Table', function (): void {
                    fluent_admin_example_data_tab();
                });
        });
}

function fluent_admin_example_components_tab(): void
{
    // --- MetaboxContainer with sidebar ---
    echo '<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">';
    echo '<div id="post-body-content">';

    // Metabox with ButtonGroup
    echo Metabox::make('Quick Actions')
        ->content(
            ButtonGroup::make()
                ->add(Button::make('Run Sync', '#')->primary())
                ->add(Button::make('Clear Cache', '#'))
                ->add(Button::make('Export', '#')->small())
        );

    // Metabox collapsed
    echo Metabox::make('Collapsed Panel')
        ->id('collapsed-panel')
        ->closed()
        ->content('<p>This panel starts collapsed.</p>');

    echo '</div>';
    echo '<div id="postbox-container-1" class="postbox-container">';

    // Sidebar: Cards + Counter + Spinner + Dashicons
    echo Card::make('Status')
        ->content(function (): void {
            echo '<p>' . Dashicon::make('yes-alt') . ' API Connected</p>';
            echo '<p>' . Dashicon::make('warning') . ' 2 warnings</p>';
            echo Spinner::make(false);
        })
        ->footer(Counter::make(5)->menuStyle());

    echo '</div>';
    echo '</div></div>';
}

function fluent_admin_example_fields_tab(): void
{
    echo '<form method="post" action="">';
    wp_nonce_field('fluent_admin_example_save', 'fluent_admin_nonce');

    echo Metabox::make('API Configuration')
        ->content(
            FormTable::make()
                ->text('api_endpoint', 'API Endpoint', [
                    'placeholder' => 'https://api.example.com',
                    'description' => 'The base URL for API requests.',
                ])
                ->password('api_key', 'API Key', [
                    'description' => 'Keep this secret.',
                ])
                ->select('environment', 'Environment', [
                    'production'  => 'Production',
                    'staging'     => 'Staging',
                    'development' => 'Development',
                ], ['value' => 'production'])
                ->checkbox('verify_ssl', 'Verify SSL', [
                    'checked'     => true,
                    'description' => 'Disable only for local development.',
                ])
                ->radio('log_level', 'Log Level', [
                    'error'   => 'Errors only',
                    'warning' => 'Warnings and errors',
                    'debug'   => 'All messages',
                ], ['value' => 'error'])
                ->textarea('notes', 'Notes', [
                    'rows'        => 4,
                    'placeholder' => 'Optional notes...',
                ])
        );

    echo Button::make('Save Settings')->primary()->submit();
    echo '</form>';
}

function fluent_admin_example_data_tab(): void
{
    $sampleData = [
        ['name' => 'Alice Johnson', 'role' => 'Administrator', 'date' => '2024-01-15'],
        ['name' => 'Bob Smith',     'role' => 'Editor',        'date' => '2024-02-20'],
        ['name' => 'Carol White',   'role' => 'Author',        'date' => '2024-03-10'],
        ['name' => 'Dave Brown',    'role' => 'Subscriber',    'date' => '2024-04-05'],
    ];

    echo Metabox::make('Sample Data Table')
        ->content(
            DataTable::make(['name' => 'Name', 'role' => 'Role', 'date' => 'Joined'])
                ->rows($sampleData)
                ->striped()
        );
}
