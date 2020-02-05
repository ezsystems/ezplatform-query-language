<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Visitor;

use eZ\Publish\API\Repository\Values\Content\Query;

final class QueryVisitorResult
{
    public const TARGET_CONTENT = 1;
    public const TARGET_CONTENT_INFO = 2;
    public const TARGET_LOCATION = 3;

    /** @var int */
    private $target;

    /** @var \eZ\Publish\API\Repository\Values\Content\Query */
    private $query;

    public function __construct(int $target, Query $query)
    {
        $this->target = $target;
        $this->query = $query;
    }

    public function getTarget(): int
    {
        return $this->target;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }
}
