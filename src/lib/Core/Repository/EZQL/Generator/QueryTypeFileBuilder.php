<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Generator;

use Zend\Code\DeclareStatement;
use Zend\Code\Generator\FileGenerator;

final class QueryTypeFileBuilder
{
    /** @var \Zend\Code\Generator\FileGenerator */
    private $file;

    public function __construct(string $namespace)
    {
        $this->file = new FileGenerator();
        $this->file->setNamespace($namespace);
        $this->file->setDeclares([
            DeclareStatement::strictTypes(1),
        ]);
    }

    public function addSymbolsTable(SymbolsTable $symbols): self
    {
        foreach ($symbols as $class => $alias) {
            $this->file->setUse($class, $alias);
        }

        return $this;
    }

    public function addQueryTypeClass(QueryTypeClassBuilder $classBuilder): self
    {
        $this->file->setClass($classBuilder->build());

        return $this;
    }

    public function build(): FileGenerator
    {
        return $this->file;
    }
}
