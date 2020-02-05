<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\API\Repository;

use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use EzSystems\EzPlatformQueryLanguage\API\Repository\EZQL\EZQLStatement;

interface EZQL
{
    public function prepare(string $query): EZQLStatement;

    public function find(
        string $query,
        array $params = [],
        array $languageFilter = [],
        bool $filterOnUserPermissions = true
    ): SearchResult;
}
