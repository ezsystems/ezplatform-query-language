<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion;

final class CriterionBinding
{
    /** @var string */
    private $class;

    /** @var callable */
    private $instantiator;

    public function __construct(string $class, callable $instantiator)
    {
        $this->class = $class;
        $this->instantiator = $instantiator;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getInstantiator(): callable
    {
        return $this->instantiator;
    }

    public function instantiate(string $operator, $value, array $attributes = []): Criterion
    {
        return $this->getInstantiator()($operator, $value, $attributes);
    }

    public static function fromSingleArgCriterion(string $class): self
    {
        return new self(
            $class,
            static function (string $operator, $value, array $attributes = []) use ($class) {
                return new $class($value);
            }
        );
    }

    public static function fromDoubleArgCriterion(string $class): self
    {
        return new self(
            $class,
            static function (string $operator, $value, array $attributes = []) use ($class) {
                return new $class($operator, $value);
            }
        );
    }
}
