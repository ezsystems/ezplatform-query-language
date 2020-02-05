<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository;

use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use EzSystems\EzPlatformQueryLanguage\API\Repository\EZQL\EZQLStatement as EZQLStatementInterface;
use EzSystems\EzPlatformQueryLanguage\API\Repository\EZQL as EZQLInterface;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\EZQLProxyInterface;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\EZQLStatement;

final class EZQL implements EZQLInterface
{
    /** @var \EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\EZQLProxyInterface */
    private $ezqlProxy;

    public function __construct(EZQLProxyInterface $ezqlProxy)
    {
        $this->ezqlProxy = $ezqlProxy;
    }

    public function prepare(string $query): EZQLStatementInterface
    {
        return new EZQLStatement($this->ezqlProxy, $query);
    }

    public function find(string $query, array $params = [], array $languageFilter = [], bool $filterOnUserPermissions = true): SearchResult
    {
        return $this->ezqlProxy->execute($query, $params, $languageFilter, $filterOnUserPermissions);
    }
}
