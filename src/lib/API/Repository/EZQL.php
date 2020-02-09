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
    /**
     * Prepare an EZQL statement for execution.
     */
    public function prepare(string $query): EZQLStatement;

    /**
     * Prepare and execute an EZQL statement.
     *
     * Shortcut for:
     *
     *     $stmt = $ezql->prepare($query);
     *     $stmt->setParams($params);
     *     $stmt->setLanguageFilter($languageFilter);
     *     $stmt->setFilterOnPermissions($filterOnUserPermissions);
     *
     *     $results = $stmt->execute();
     */
    public function find(
        string $query,
        array $params = [],
        array $languageFilter = [],
        bool $filterOnUserPermissions = true
    ): SearchResult;
}
