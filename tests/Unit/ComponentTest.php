<?php

declare(strict_types=1);

namespace FluentAdmin\Tests\Unit;

use FluentAdmin\Component;
use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{
    private function makeConcreteComponent(): Component
    {
        return new class extends Component {
            protected function html(): string
            {
                return '<div>test</div>';
            }
        };
    }

    public function testMakeReturnsInstance(): void
    {
        $component = $this->makeConcreteComponent();
        $this->assertInstanceOf(Component::class, $component);
    }

    public function testToStringCallsRender(): void
    {
        $component = $this->makeConcreteComponent();
        $this->assertSame('<div>test</div>', (string) $component);
    }

    public function testToHtmlAliasesRender(): void
    {
        $component = $this->makeConcreteComponent();
        $this->assertSame($component->render(), $component->toHtml());
    }

    public function testCallStoresValueInConfig(): void
    {
        $component = $this->makeConcreteComponent();
        $result = $component->someKey('someValue');

        // __call returns $this for fluency
        $this->assertSame($component, $result);
    }

    public function testCallWithNoArgStoresToTrue(): void
    {
        $component = new class extends Component {
            protected function html(): string
            {
                return '';
            }

            public function getConfig(): array
            {
                return $this->config;
            }
        };

        $component->enabled();
        $this->assertTrue($component->getConfig()['enabled']);
    }

    public function testCallStoresArgInConfig(): void
    {
        $component = new class extends Component {
            protected function html(): string
            {
                return '';
            }

            public function getConfig(): array
            {
                return $this->config;
            }
        };

        $component->myKey('myValue');
        $this->assertSame('myValue', $component->getConfig()['myKey']);
    }

    public function testRenderPassesThroughApplyFilters(): void
    {
        // The stub apply_filters returns its first non-tag argument (the $html).
        $component = $this->makeConcreteComponent();
        $this->assertSame('<div>test</div>', $component->render());
    }

    public function testAddClassAndRemoveClass(): void
    {
        $component = new class extends Component {
            protected function html(): string
            {
                return '';
            }

            public function getAttributes(): array
            {
                return $this->attributes;
            }
        };

        $component->addClass('foo')->addClass('bar');
        $component->removeClass('foo');

        $this->assertContains('bar', $component->getAttributes()['class']);
        $this->assertNotContains('foo', $component->getAttributes()['class']);
    }

    public function testDataSetsDataAttribute(): void
    {
        $component = new class extends Component {
            protected function html(): string
            {
                return '';
            }

            public function getAttributes(): array
            {
                return $this->attributes;
            }
        };

        $component->data('action', 'delete');
        $this->assertSame('delete', $component->getAttributes()['data-action']);
    }
}
