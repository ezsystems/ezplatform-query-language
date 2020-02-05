<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\QueryType;

use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\Core\QueryType\OptionsResolverBasedQueryType;
use EzSystems\EzPlatformQueryLanguage\API\Repository\EZQL;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EZQLQueryType extends OptionsResolverBasedQueryType
{
    /** @var \EzSystems\EzPlatformQueryLanguage\API\Repository\EZQL */
    private $ezql;

    public function __construct(EZQL $ezql)
    {
        $this->ezql = $ezql;
    }

    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setRequired('query');
        $optionsResolver->setDefaults([
            'bind' => [],
        ]);

        $optionsResolver->setAllowedTypes('query', 'string');
        $optionsResolver->setAllowedTypes('bind', 'array');
    }

    protected function doGetQuery(array $parameters): Query
    {
        $query = $parameters['query'];
        $params = $parameters['bind'];

        return $this->ezql->prepare($query)->getQuery($params);
    }

    public static function getName(): string
    {
        return 'EZQL';
    }
}
