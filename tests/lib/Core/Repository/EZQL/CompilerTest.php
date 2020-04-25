<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Tests\Core\Repository\EZQL;

use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\InputStream;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLLexer;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLParser;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Visitor\QueryCompilerVisitor;
use FilesystemIterator;
use PHPUnit\Framework\TestCase;

final class CompilerTest extends TestCase
{
    private const FIXTURE_DIR = __DIR__ . '/Fixtures';
    private const OUTPUT_SEPARATOR = "--EXPECTED OUTPUT--\n";

    private const EXPECTED_CLASSNAME = 'ExampleQuery';
    private const EXPECTED_NAMESPACE = 'App\QueryType';

    /**
     * @dataProvider dateProviderForTestCompile
     */
    public function testCompilerQuery(string $query, string $expectedOutput): void
    {
        $lexer = new EZQLLexer(InputStream::fromString($query));
        $parser = new EZQLParser(new CommonTokenStream($lexer));

        $visitor = new QueryCompilerVisitor(
            self::EXPECTED_NAMESPACE,
            self::EXPECTED_CLASSNAME
        );

        $this->assertEquals(
            $expectedOutput,
            $parser->stmt()->accept($visitor)
        );
    }

    public function dateProviderForTestCompile(): iterable
    {
        $fixtures = new FilesystemIterator(
            self::FIXTURE_DIR,
            FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS
        );

        foreach ($fixtures as $fixtureName) {
            $fixture = file_get_contents($fixtureName);

            list($query, $output) = explode(
                self::OUTPUT_SEPARATOR,
                $fixture
            );

            yield $fixtureName => [
                'query' => trim($query),
                'output' => $output,
            ];
        }
    }
}
