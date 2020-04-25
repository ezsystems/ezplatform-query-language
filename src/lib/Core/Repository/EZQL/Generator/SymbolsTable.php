<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Generator;

use ArrayIterator;
use Iterator;
use IteratorAggregate;
use ReflectionClass;

final class SymbolsTable implements IteratorAggregate
{
    /** @var string[] */
    private $symbols;

    public function __construct()
    {
        $this->symbols = [];
    }

    public function add(string $class, ?string $alias = null): string
    {
        if (!$this->contains($class)) {
            $this->symbols[$class] = $alias;

            return $alias ?? $this->getShortName($class);
        }

        return $this->symbols[$alias];
    }

    public function contains(string $class): bool
    {
        return array_key_exists($class, $this->symbols);
    }

    public function getLocalName(string $class, bool $autoImport = true): string
    {
        foreach ($this->symbols as $name => $alias) {
            if ($name === $class) {
                return $alias ?? $this->getShortName($name);
            }
        }

        if ($autoImport) {
            return $this->add($class);
        }

        return $class;
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->symbols);
    }

    private function getShortName(string $class): string
    {
        return (new ReflectionClass($class))->getShortName();
    }
}
