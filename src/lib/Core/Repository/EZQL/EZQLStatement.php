<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL;

use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use EzSystems\EzPlatformQueryLanguage\API\Repository\EZQL\EZQLStatement as EQZLStatementInterface;

final class EZQLStatement implements EQZLStatementInterface
{
    /** @var \EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\EZQLProxyInterface */
    private $proxy;

    /** @var string */
    private $ezqlQuery;

    /** @var array */
    private $params = [];

    /** @var array */
    private $languagesFilter = [];

    /** @var bool */
    private $filterOnPermissions = true;

    public function __construct(EZQLProxyInterface $proxy, string $query)
    {
        $this->proxy = $proxy;
        $this->ezqlQuery = $query;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function bindParam(string $parameter, $value): void
    {
        $this->params[$parameter] = $value;
    }

    public function getEZQLQuery(): string
    {
        return $this->ezqlQuery;
    }

    public function getQuery(array $params = null): Query
    {
        if (is_array($params)) {
            $this->params = $params;
        }

        return $this->proxy->getQuery($this->ezqlQuery, $this->params);
    }

    public function execute(array $params = null): SearchResult
    {
        if (is_array($params)) {
            $this->params = $params;
        }

        return $this->proxy->execute(
            $this->ezqlQuery,
            $this->params,
            $this->languagesFilter,
            $this->filterOnPermissions
        );
    }

    public function getFilterOnPermissions(): bool
    {
        return $this->filterOnPermissions;
    }

    public function setFilterOnPermissions(bool $filterOnPermissions): void
    {
        $this->filterOnPermissions = $filterOnPermissions;
    }

    public function getLanguageFilter(): array
    {
        return $this->languagesFilter;
    }

    public function setLanguageFilter(array $languagesFilter): void
    {
        $this->languagesFilter = $languagesFilter;
    }

    public function __toString()
    {
        return $this->ezqlQuery;
    }
}
