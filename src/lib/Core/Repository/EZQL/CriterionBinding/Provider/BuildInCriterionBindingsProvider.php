<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding\Provider;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding\CriterionBinding;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding\CriterionBindingsProviderInterface;

final class BuildInCriterionBindingsProvider implements CriterionBindingsProviderInterface
{
    public function getBindings(): array
    {
        return [
            CriterionBinding::fromSingleArgCriterion(Criterion\Ancestor::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\ContentId::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\ContentTypeGroupId::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\ContentTypeIdentifier::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\LanguageCode::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\LocationId::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\LocationRemoteId::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\ObjectStateId::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\ParentLocationId::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\RemoteId::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\SectionId::class),
            CriterionBinding::fromSingleArgCriterion(Criterion\Subtree::class),
            CriterionBinding::fromDoubleArgCriterion(Criterion\Location\Depth::class),
            CriterionBinding::fromDoubleArgCriterion(Criterion\Location\Priority::class),
        ];
    }
}
