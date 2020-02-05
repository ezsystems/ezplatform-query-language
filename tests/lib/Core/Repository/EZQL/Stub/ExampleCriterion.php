<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Tests\Core\Repository\EZQL\Stub;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion;

final class ExampleCriterion extends Criterion
{
    /** @var array */
    private $args;

    public function __construct(...$args)
    {
        $this->args = $args;
    }

    public function getSpecifications(): array
    {
        return [];
    }
}
