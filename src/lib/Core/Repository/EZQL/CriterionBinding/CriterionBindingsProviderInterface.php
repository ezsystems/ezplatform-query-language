<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding;

interface CriterionBindingsProviderInterface
{
    /**
     * @return \EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding\CriterionBinding[]
     */
    public function getBindings(): array;
}
