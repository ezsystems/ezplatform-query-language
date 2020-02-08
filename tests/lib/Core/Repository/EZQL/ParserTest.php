<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Tests\Core\Repository\EZQL;

use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\InputStream;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\DateMetadata;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Field;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\FieldRelation;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\FullText;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\IsFieldEmpty;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Location\IsMainLocation;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LocationId;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LogicalAnd;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LogicalNot;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LogicalOr;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\MatchAll;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\MatchNone;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\UserMetadata;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Visibility;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause\ContentName;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause\Location\Priority;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding\CriterionBinding;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\ErrorListener\ExceptionErrorListener;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Exception\MissingParameterException;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Exception\SyntaxErrorException;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLLexer;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLParser;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Visitor\QueryVisitor;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Visitor\QueryVisitorResult;
use EzSystems\EzPlatformQueryLanguage\Tests\Core\Repository\EZQL\Stub\ExampleCriterion;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    /**
     * @dataProvider dataProviderForSelectContent
     */
    public function testSelectContent(string $ezql, array $params, Query $query): void
    {
        $this->assertEquals(
            new QueryVisitorResult(
                QueryVisitorResult::TARGET_CONTENT,
                $query
            ),
            $this->doVisitQuery($ezql, $params)
        );
    }

    /**
     * @dataProvider dataProviderForSelectContentInfo
     */
    public function testSelectContentInfo(string $ezql, array $params, Query $query): void
    {
        $this->assertEquals(
            new QueryVisitorResult(
                QueryVisitorResult::TARGET_CONTENT_INFO,
                $query
            ),
            $this->doVisitQuery($ezql, $params)
        );
    }

    /**
     * @dataProvider dataProviderForSelectLocation
     */
    public function testSelectLocation(string $ezql, array $params, LocationQuery $query): void
    {
        $this->assertEquals(
            new QueryVisitorResult(
                QueryVisitorResult::TARGET_LOCATION,
                $query
            ),
            $this->doVisitQuery($ezql, $params)
        );
    }

    public function dataProviderForSelectContent(): iterable
    {
        yield 'select with limit' => [
            'SELECT CONTENT LIMIT 100',
            [],
            new Query([
                'limit' => 100,
            ]),
        ];

        yield 'select with offset' => [
            'SELECT CONTENT OFFSET 100',
            [],
            new Query([
                'offset' => 100,
            ]),
        ];

        yield 'select with limit and offset' => [
            'SELECT CONTENT LIMIT 100 OFFSET 25',
            [],
            new Query([
                'limit' => 100,
                'offset' => 25,
            ]),
        ];

        foreach ($this->generateCriterion() as $name => [$expression, $criterion]) {
            yield 'filter by ' . $name => [
                sprintf('SELECT CONTENT FILTER BY %s', $expression),
                [],
                new Query([
                    'filter' => $criterion,
                ]),
            ];
        }

        foreach ($this->generateCriterion() as $name => [$expression, $criterion]) {
            yield 'query ' . $name => [
                sprintf('SELECT CONTENT QUERY %s', $expression),
                [],
                new Query([
                    'query' => $criterion,
                ]),
            ];
        }

        foreach ($this->generateSortClauses() as $name => [$expression, $sortClauses]) {
            yield 'sort ' . $name => [
                sprintf('SELECT CONTENT ORDER BY %s', $expression),
                [],
                new Query([
                    'sortClauses' => $sortClauses,
                ]),
            ];
        }
    }

    public function dataProviderForSelectContentInfo(): iterable
    {
        yield 'select with limit' => [
            'SELECT CONTENT INFO LIMIT 100',
            [],
            new Query([
                'limit' => 100,
            ]),
        ];

        yield 'select with offset' => [
            'SELECT CONTENT INFO OFFSET 100',
            [],
            new Query([
                'offset' => 100,
            ]),
        ];

        yield 'select with limit and offset' => [
            'SELECT CONTENT INFO LIMIT 100 OFFSET 25',
            [],
            new Query([
                'limit' => 100,
                'offset' => 25,
            ]),
        ];

        foreach ($this->generateCriterion() as $name => [$expression, $criterion]) {
            yield 'filter by ' . $name => [
                sprintf('SELECT CONTENT INFO FILTER BY %s', $expression),
                [],
                new Query([
                    'filter' => $criterion,
                ]),
            ];
        }

        foreach ($this->generateCriterion() as $name => [$expression, $criterion]) {
            yield 'query ' . $name => [
                sprintf('SELECT CONTENT INFO QUERY %s', $expression),
                [],
                new Query([
                    'query' => $criterion,
                ]),
            ];
        }

        foreach ($this->generateSortClauses() as $name => [$expression, $sortClauses]) {
            yield 'sort ' . $name => [
                sprintf('SELECT CONTENT INFO ORDER BY %s', $expression),
                [],
                new Query([
                    'sortClauses' => $sortClauses,
                ]),
            ];
        }
    }

    public function dataProviderForSelectLocation(): iterable
    {
        yield 'select with limit' => [
            'SELECT LOCATION LIMIT 100',
            [],
            new LocationQuery([
                'limit' => 100,
            ]),
        ];

        yield 'select with offset' => [
            'SELECT LOCATION OFFSET 100',
            [],
            new LocationQuery([
                'offset' => 100,
            ]),
        ];

        yield 'select with limit and offset' => [
            'SELECT LOCATION LIMIT 100 OFFSET 25',
            [],
            new LocationQuery([
                'limit' => 100,
                'offset' => 25,
            ]),
        ];

        foreach ($this->generateCriterion() as $name => [$expression, $criterion]) {
            yield 'filter by ' . $name => [
                sprintf('SELECT LOCATION FILTER BY %s', $expression),
                [],
                new LocationQuery([
                    'filter' => $criterion,
                ]),
            ];
        }

        foreach ($this->generateCriterion() as $name => [$expression, $criterion]) {
            yield 'query ' . $name => [
                sprintf('SELECT LOCATION QUERY %s', $expression),
                [],
                new LocationQuery([
                    'query' => $criterion,
                ]),
            ];
        }

        foreach ($this->generateSortClauses() as $name => [$expression, $sortClauses]) {
            yield 'sort ' . $name => [
                sprintf('SELECT LOCATION ORDER BY %s', $expression),
                [],
                new LocationQuery([
                    'sortClauses' => $sortClauses,
                ]),
            ];
        }
    }

    public function generateCriterion(): iterable
    {
        yield 'match all' => [
            'MATCH ALL',
            new MatchAll(),
        ];

        yield 'match none' => [
            'MATCH NONE',
            new MatchNone(),
        ];

        yield 'is main location' => [
            'IS MAIN LOCATION',
            new IsMainLocation(IsMainLocation::MAIN),
        ];

        yield 'is not main location' => [
            'IS NOT MAIN LOCATION',
            new IsMainLocation(IsMainLocation::NOT_MAIN),
        ];

        yield 'is visible' => [
            'IS VISIBLE',
            new Visibility(Visibility::VISIBLE),
        ];

        yield 'is hidden' => [
            'IS HIDDEN',
            new Visibility(Visibility::HIDDEN),
        ];

        foreach ($this->generateOperators() as $op => [$tail, $value]) {
            yield "field is $op" => [
                "FIELD foo $tail",
                new Field('foo', $op, $value),
            ];
        }

        yield 'field relation in' => [
            'FIELD RELATION foo IN (1, 10, 100)',
            new FieldRelation('foo', Operator::IN, [1, 10, 100]),
        ];

        yield 'field relation not in' => [
            'FIELD RELATION foo NOT IN (1, 10, 100)',
            new LogicalNot(new FieldRelation('foo', Operator::IN, [1, 10, 100])),
        ];

        yield 'field relation contains' => [
            'FIELD RELATION foo CONTAINS 10',
            new FieldRelation('foo', Operator::CONTAINS, 10),
        ];

        yield 'field foo is empty' => [
            'FIELD foo IS EMPTY ',
            new IsFieldEmpty('foo', true),
        ];

        yield 'field foo is not empty' => [
            'FIELD foo IS NOT EMPTY ',
            new IsFieldEmpty('foo', false),
        ];

        foreach ($this->generateLocationPriorityCriterion() as $name => [$expression, $criterion]) {
            yield $name => [
                $expression,
                $criterion,
            ];
        }

        foreach ([DateMetadata::CREATED, DateMetadata::MODIFIED] as $target) {
            foreach ($this->dataProviderForDateMetadata($target) as $name => [$expression, $criterion]) {
                yield $name => [
                    $expression,
                    $criterion,
                ];
            }
        }

        foreach ([UserMetadata::MODIFIER, UserMetadata::GROUP, UserMetadata::OWNER] as $target) {
            foreach ($this->generateUserMetadata($target) as $name => [$expression, $criterion]) {
                yield $name => [
                    $expression,
                    $criterion,
                ];
            }
        }

        yield 'fulltext' => [
            'FULLTEXT "foo"',
            new FullText('foo'),
        ];

        yield 'fulltext with fuzziness' => [
            'FULLTEXT "foo" FUZZINESS 0.5',
            new FullText('foo', [
                'fuzziness' => 0.5,
            ]),
        ];

        yield 'fulltext with boost' => [
            'FULLTEXT "foo" BOOST bar^2, baz^2.5, foobar^3.0',
            new FullText('foo', [
                'boost' => [
                    'bar' => 2,
                    'baz' => 2.5,
                    'foobar' => 3.0,
                ],
            ]),
        ];

        yield 'fulltext with fuzziness and boost' => [
            'FULLTEXT "foo" FUZZINESS 0.5 BOOST bar^2, baz^2.5, foobar^3.0',
            new FullText('foo', [
                'fuzziness' => 0.5,
                'boost' => [
                    'bar' => 2,
                    'baz' => 2.5,
                    'foobar' => 3.0,
                ],
            ]),
        ];

        yield 'LocationId' => [
            'LocationId = 2',
            new LocationId(2),
        ];

        foreach ($this->generateOperators() as $op => [$tail, $value]) {
            yield "class $op" => [
                ExampleCriterion::class . " $tail",
                new ExampleCriterion($op, $value, []),
            ];
        }

        yield 'operator: and' => [
            sprintf('%1$s = "foo" AND %1$s = "bar"', ExampleCriterion::class),
            new LogicalAnd([
                new ExampleCriterion(Operator::EQ, 'foo', []),
                new ExampleCriterion(Operator::EQ, 'bar', []),
            ]),
        ];

        yield 'operator: or' => [
            sprintf('%1$s = "foo" OR %1$s = "bar"', ExampleCriterion::class),
            new LogicalOr([
                new ExampleCriterion(Operator::EQ, 'foo', []),
                new ExampleCriterion(Operator::EQ, 'bar', []),
            ]),
        ];

        yield 'expression grouping' => [
            sprintf('(%1$s = "foo" OR %1$s = "bar") AND %1$s = "baz"', ExampleCriterion::class),
            new LogicalAnd([
                new LogicalOr([
                    new ExampleCriterion(Operator::EQ, 'foo', []),
                    new ExampleCriterion(Operator::EQ, 'bar', []),
                ]),
                new ExampleCriterion(Operator::EQ, 'baz', []),
            ]),
        ];
    }

    public function generateLocationPriorityCriterion(): iterable
    {
        yield 'location priority between' => [
            'LOCATION PRIORITY BETWEEN 1..100',
            new Query\Criterion\Location\Priority(Operator::BETWEEN, [1, 100]),
        ];

        yield 'location priority >' => [
            'LOCATION PRIORITY > 100',
            new Query\Criterion\Location\Priority(Operator::GT, 100),
        ];

        yield 'location priority >=' => [
            'LOCATION PRIORITY >= 100',
            new Query\Criterion\Location\Priority(Operator::GTE, 100),
        ];

        yield 'location priority <' => [
            'LOCATION PRIORITY < 100',
            new Query\Criterion\Location\Priority(Operator::LT, 100),
        ];

        yield 'location priority <=' => [
            'LOCATION PRIORITY <= 100',
            new Query\Criterion\Location\Priority(Operator::LTE, 100),
        ];
    }

    public function dataProviderForDateMetadata(string $target): iterable
    {
        $value = time();

        yield "$target =" => [
            "$target = $value",
            new DateMetadata($target, Operator::EQ, $value),
        ];

        yield "$target >" => [
            "$target > $value",
            new DateMetadata($target, Operator::GT, $value),
        ];

        yield "$target >=" => [
            "$target >= $value",
            new DateMetadata($target, Operator::GTE, $value),
        ];

        yield "$target <" => [
            "$target < $value",
            new DateMetadata($target, Operator::LT, $value),
        ];

        yield "$target <=" => [
            "$target <= $value",
            new DateMetadata($target, Operator::LTE, $value),
        ];

        yield "$target BETWEEN" => [
            "$target BETWEEN 10..100",
            new DateMetadata($target, Operator::BETWEEN, [10, 100]),
        ];

        yield "$target IN" => [
            "$target IN (10, 20, 30)",
            new DateMetadata($target, Operator::IN, [10, 20, 30]),
        ];
    }

    public function generateUserMetadata(string $target): iterable
    {
        yield "$target =" => [
            sprintf('%s = "foo"', $target),
            new UserMetadata($target, Operator::EQ, 'foo'),
        ];

        yield "$target in" => [
            sprintf('%s IN ("foo", "bar", "baz")', $target),
            new UserMetadata($target, Operator::IN, ['foo', 'bar', 'baz']),
        ];
    }

    public function generateOperators(): iterable
    {
        yield Operator::EQ => ['= 100', 100];
        yield Operator::GT => ['> 100', 100];
        yield Operator::LT => ['< 100', 100];

        yield Operator::GTE => ['>= 100', 100];
        yield Operator::LTE => ['<= 100', 100];

        yield Operator::IN => [
            'in ("X", "Y", "Z")',
            ['X', 'Y', 'Z'],
        ];

        yield Operator::BETWEEN => [
            'BETWEEN 1..100',
            [1, 100],
        ];

        yield Operator::CONTAINS => [
            'CONTAINS "foo"',
            'foo',
        ];

        yield Operator::LIKE => [
            'LIKE "foo"',
            'foo',
        ];
    }

    public function testVisitorThrowsMissingParameterException(): void
    {
        $this->expectException(MissingParameterException::class);
        $this->expectErrorMessage('Missing parameter: query');

        $this->doVisitQuery(
            'SELECT LOCATION QUERY CustomCriterion = :query',
            [
                /* No parameters */
            ]
        );
    }

    public function testParseError(): void
    {
        $this->expectException(SyntaxErrorException::class);
        $this->expectErrorMessage("Line 1:21 mismatched input 'FullText' expecting {K_FALSE, K_TRUE, INT, DOUBLE, STRING, PARAMETER_NAME}");

        $this->doVisitQuery('SELECT CONTENT LIMIT FullText = "foo"');
    }

    private function generateSortClauses(): iterable
    {
        yield 'single clause ASC' => [
            'ContentName ASC',
            [
                new ContentName(Query::SORT_ASC),
            ],
        ];

        yield 'single clause DESC' => [
            'ContentName DESC',
            [
                new ContentName(Query::SORT_DESC),
            ],
        ];

        yield 'multiple clauses' => [
            'ContentName DESC, Location\Priority ASC',
            [
                new ContentName(Query::SORT_DESC),
                new Priority(Query::SORT_ASC),
            ],
        ];
    }

    private function doVisitQuery(string $query, array $params = []): QueryVisitorResult
    {
        $lexer = new EZQLLexer(InputStream::fromString($query));

        $parser = new EZQLParser(new CommonTokenStream($lexer));
        $parser->removeErrorListeners();
        $parser->addErrorListener(new ExceptionErrorListener());

        $visitor = new QueryVisitor([
            LocationId::class => CriterionBinding::fromSingleArgCriterion(LocationId::class),
            ExampleCriterion::class => new CriterionBinding(
                ExampleCriterion::class,
                static function (...$args): ExampleCriterion {
                    return new ExampleCriterion(...$args);
                }
            ),
        ], $params);

        return $parser->stmt()->accept($visitor);
    }
}
