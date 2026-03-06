<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Components;

use FluentAdmin\Components\Metabox;
use FluentAdmin\Components\Notice;
use PHPUnit\Framework\TestCase;

class MetaboxTest extends TestCase
{
    public function testTitleIsRenderedAndEscaped(): void
    {
        $html = Metabox::make('<b>Settings</b>')->render();
        $this->assertStringNotContainsString('<b>', $html);
        $this->assertStringContainsString('&lt;b&gt;Settings&lt;/b&gt;', $html);
    }

    public function testTitleInH2(): void
    {
        $html = Metabox::make('My Box')->render();
        $this->assertStringContainsString('<h2', $html);
        $this->assertStringContainsString('My Box', $html);
    }

    public function testStringContentRenderedInsideInsideDiv(): void
    {
        $html = Metabox::make('Box')->content('Hello World')->render();
        $this->assertStringContainsString('<div class="inside">', $html);
        $this->assertStringContainsString('Hello World', $html);
    }

    public function testCallableContentIsCaptured(): void
    {
        $html = Metabox::make('Box')
            ->content(function () {
                echo '<p>Captured content</p>';
            })
            ->render();

        $this->assertStringContainsString('<p>Captured content</p>', $html);
    }

    public function testComponentContentIsRendered(): void
    {
        $notice = Notice::make('OK', 'success');
        $html = Metabox::make('Box')->content($notice)->render();
        $this->assertStringContainsString('notice-success', $html);
    }

    public function testClosedClassAddedWhenSet(): void
    {
        $html = Metabox::make('Box')->closed()->render();
        $this->assertStringContainsString('closed', $html);
    }

    public function testNoClosedClassByDefault(): void
    {
        $html = Metabox::make('Box')->render();
        $this->assertStringNotContainsString('closed', $html);
    }

    public function testIdAttributeSet(): void
    {
        $html = Metabox::make('Box')->id('my-box')->render();
        $this->assertStringContainsString('id="my-box"', $html);
    }

    public function testAlwaysHasPostboxClass(): void
    {
        $html = Metabox::make('Box')->render();
        $this->assertStringContainsString('class="postbox"', $html);
    }

    public function testWrapsPostboxInMetaboxHolderForCoreHeadingStyles(): void
    {
        $html = Metabox::make('Box')->render();
        $this->assertStringContainsString('<div class="metabox-holder"><div class="postbox"', $html);
        $this->assertStringContainsString('<h2 class="hndle">', $html);
    }

    public function testStringContentIsEscaped(): void
    {
        $html = Metabox::make('Box')->content('<script>xss</script>')->render();
        $this->assertStringNotContainsString('<script>', $html);
    }
}
