<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Exception;

use Exception;
use Throwable;

final class MissingParameterException extends Exception
{
    public function __construct(string $parameterName, $code = 0, Throwable $previous = null)
    {
        parent::__construct("Missing parameter: $parameterName", $code, $previous);
    }
}
