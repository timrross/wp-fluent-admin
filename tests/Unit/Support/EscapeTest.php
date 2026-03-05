<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit\Support;

use FluentAdmin\Support\Escape;
use PHPUnit\Framework\TestCase;

class EscapeTest extends TestCase
{
    public function testHtmlEscapesSpecialChars(): void
    {
        $this->assertSame('&lt;script&gt;alert(1)&lt;/script&gt;', Escape::html('<script>alert(1)</script>'));
    }

    public function testHtmlEscapesQuotes(): void
    {
        $input = 'He said "hello" & she said \'hi\'';
        $expected = 'He said &quot;hello&quot; &amp; she said &#039;hi&#039;';
        $this->assertSame($expected, Escape::html($input));
    }

    public function testAttrEscapesSpecialChars(): void
    {
        $this->assertSame('&lt;b&gt;bold&lt;/b&gt;', Escape::attr('<b>bold</b>'));
    }

    public function testAttrEscapesQuotes(): void
    {
        $this->assertSame('&quot;quoted&quot;', Escape::attr('"quoted"'));
    }

    public function testUrlEscapesSpecialChars(): void
    {
        $escaped = Escape::url('https://example.com?foo=<bar>');
        $this->assertStringNotContainsString('<', $escaped);
        $this->assertStringNotContainsString('>', $escaped);
    }

    public function testTextareaEscapesHtml(): void
    {
        $this->assertSame('&lt;p&gt;Hello&lt;/p&gt;', Escape::textarea('<p>Hello</p>'));
    }

    public function testHtmlReturnsPlainTextUnchanged(): void
    {
        $this->assertSame('Hello World', Escape::html('Hello World'));
    }
}
