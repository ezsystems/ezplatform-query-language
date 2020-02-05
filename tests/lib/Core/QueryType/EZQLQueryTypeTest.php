<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Tests\Core\QueryType;

use eZ\Publish\API\Repository\Values\Content\Query;
use EzSystems\EzPlatformQueryLanguage\API\Repository\EZQL;
use EzSystems\EzPlatformQueryLanguage\Core\QueryType\EZQLQueryType;
use PHPUnit\Framework\TestCase;

final class EZQLQueryTypeTest extends TestCase
{
    /** @var \EzSystems\EzPlatformQueryLanguage\API\Repository\EZQL|\PHPUnit\Framework\MockObject\MockObject */
    private $ezql;

    /** @var \EzSystems\EzPlatformQueryLanguage\Core\QueryType\EZQLQueryType */
    private $queryType;

    protected function setUp(): void
    {
        $this->ezql = $this->createMock(EZQL::class);
        $this->queryType = new EZQLQueryType($this->ezql);
    }

    public function testGetQuery(): void
    {
        $query = 'SELECT LOCATION FILTER BY LocationId = :locationId';
        $params = [
            'locationId' => 1,
        ];

        $expectedResult = $this->createMock(Query::class);

        $stmt = $this->createMock(EZQL\EZQLStatement::class);
        $stmt
            ->expects($this->once())
            ->method('getQuery')
            ->with($params)
            ->willReturn($expectedResult);

        $this->ezql
            ->expects($this->once())
            ->method('prepare')
            ->with($query)
            ->willReturn($stmt);

        $actualResult = $this->queryType->getQuery([
            'query' => $query,
            'bind' => $params,
        ]);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetSupportedParameters(): void
    {
        $this->assertEquals(['query', 'bind'], $this->queryType->getSupportedParameters());
    }

    public function testGetName(): void
    {
        $this->assertEquals('EZQL', EZQLQueryType::getName());
    }
}
