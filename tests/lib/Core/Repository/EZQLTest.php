<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Tests\Core\Repository;

use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\EZQLProxyInterface;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\EZQLStatement;
use PHPUnit\Framework\TestCase;

final class EZQLTest extends TestCase
{
    private const EXAMPLE_QUERY = 'SELECT LOCATION FILTER BY LOCATION ID = :locationId';

    private const EXAMPLE_PARAMS = [
        'locationId' => 2,
    ];

    private const EXAMPLE_LANGUAGE_FILTER = [
        'languages' => [
            'pol-PL',
            'eng-GB',
        ],
        'useAlwaysAvailable' => true,
    ];

    /** @var \EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\EZQLProxyInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $proxy;

    /** @var \EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL */
    private $ezql;

    public function testPrepare(): void
    {
        $expectedResult = new EZQLStatement($this->proxy, self::EXAMPLE_QUERY);
        $actualResult = $this->ezql->prepare(self::EXAMPLE_QUERY);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testFind(): void
    {
        $expectedResult = $this->createMock(SearchResult::class);

        $args = [
            self::EXAMPLE_QUERY,
            self::EXAMPLE_PARAMS,
            self::EXAMPLE_LANGUAGE_FILTER,
            true,
        ];

        $this->proxy
            ->expects($this->once())
            ->method('execute')
            ->with(...$args)
            ->willReturn($expectedResult);

        $actualResult = $this->ezql->find(...$args);

        $this->assertEquals($expectedResult, $actualResult);
    }

    protected function setUp(): void
    {
        $this->proxy = $this->createMock(EZQLProxyInterface::class);
        $this->ezql = new EZQL($this->proxy);
    }
}
