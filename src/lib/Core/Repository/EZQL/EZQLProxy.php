<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL;

use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\InputStream;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding\CriterionBinding;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding\CriterionBindingsProviderInterface;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\ErrorListener\ExceptionErrorListener;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLLexer;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLParser;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Visitor\QueryVisitor;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Visitor\QueryVisitorResult;

/**
 * @internal
 */
final class EZQLProxy implements EZQLProxyInterface
{
    /** @var \eZ\Publish\API\Repository\SearchService */
    private $searchService;

    /** @var array<string,CriterionBinding> */
    private $criterions = [];

    public function __construct(SearchService $searchService, iterable $criterionBindingsProviders)
    {
        $this->searchService = $searchService;
        foreach ($criterionBindingsProviders as $provider) {
            $this->registerCriterionBindingsProvider($provider);
        }
    }

    public function execute(string $query, array $params = [], array $languageFilter = [], bool $filterOnUserPermissions = true): SearchResult
    {
        $result = $this->visit($query, $params);

        switch ($result->getTarget()) {
            case QueryVisitorResult::TARGET_CONTENT:
                return $this->searchService->findContent($result->getQuery(), $languageFilter, $filterOnUserPermissions);
            case QueryVisitorResult::TARGET_CONTENT_INFO:
                return $this->searchService->findContentInfo($result->getQuery(), $languageFilter, $filterOnUserPermissions);
            case QueryVisitorResult::TARGET_LOCATION:
                return $this->searchService->findLocations($result->getQuery(), $languageFilter, $filterOnUserPermissions);
        }
    }

    public function getQuery(string $query, array $params = []): Query
    {
        return $this->visit($query, $params)->getQuery();
    }

    private function visit(string $query, array $params): QueryVisitorResult
    {
        $lexer = new EZQLLexer(InputStream::fromString($query));

        $parser = new EZQLParser(new CommonTokenStream($lexer));
        $parser->removeErrorListeners();
        $parser->addErrorListener(new ExceptionErrorListener());

        return $parser->stmt()->accept(new QueryVisitor($this->criterions, $params));
    }

    private function registerCriterionBinding(CriterionBinding $binding): void
    {
        $this->criterions[$binding->getClass()] = $binding;
    }

    private function registerCriterionBindingsProvider(CriterionBindingsProviderInterface $provider): void
    {
        foreach ($provider->getBindings() as $binding) {
            $this->registerCriterionBinding($binding);
        }
    }
}
