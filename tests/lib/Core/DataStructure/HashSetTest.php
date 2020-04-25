<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Tests\Core\DataStructure;

use EzSystems\EzPlatformQueryLanguage\Core\DataStructure\HashSet;
use PHPUnit\Framework\TestCase;

final class HashSetTest extends TestCase
{
    public function testIsEmptyReturnsTrue(): void
    {
        $set = new HashSet();

        $this->assertTrue($set->isEmpty());
    }

    public function testIsEmptyReturnsFalse(): void
    {
        $set = new HashSet(['Foo']);

        $this->assertFalse($set->isEmpty());
    }

    public function testAdd(): void
    {
        $set = new HashSet();
        $this->assertFalse($set->contains('A'));
        $set->add('A');
        $this->assertTrue($set->contains('A'));
    }

    public function testCount(): void
    {
        $this->assertEquals(0, (new HashSet())->count());
        $this->assertEquals(3, (new HashSet(['a', 'b', 'c']))->count());
    }

    public function testContainsReturnsTrue(): void
    {
        $this->assertTrue((new HashSet(['a', 'b', 'c']))->contains('a'));
    }

    public function testContainsReturnsFalse(): void
    {
        $this->assertFalse((new HashSet(['a', 'b', 'c']))->contains('X'));
    }

    public function testToArray(): void
    {
        $this->assertEquals(
            [],
            (new HashSet())->toArray()
        );

        $this->assertEquals(
            ['a', 'b', 'c'],
            (new HashSet(['a', 'b', 'c']))->toArray()
        );
    }
}
