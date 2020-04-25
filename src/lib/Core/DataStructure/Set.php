<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformQueryLanguage\Core\DataStructure;

use Countable;
use Iterator;
use IteratorAggregate;

interface Set extends IteratorAggregate, Countable
{
    public function add($value): void;

    public function contains($value): bool;

    public function count(): int;

    public function isEmpty(): bool;

    public function getIterator(): Iterator;

    public function toArray(): array;
}
