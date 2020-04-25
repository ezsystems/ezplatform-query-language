<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\DataStructure;

use ArrayIterator;
use Iterator;

final class HashSet implements Set
{
    /** @var mixed[] */
    private $values;

    public function __construct(array $values = [])
    {
        $this->values = array_unique($values);
    }

    public function add($value): void
    {
        if (!$this->contains($value)) {
            $this->values[] = $value;
        }
    }

    public function contains($value): bool
    {
        return in_array($value, $this->values);
    }

    public function count(): int
    {
        return count($this->values);
    }

    public function isEmpty(): bool
    {
        return empty($this->values);
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->values);
    }

    public function toArray(): array
    {
        return $this->values;
    }
}
