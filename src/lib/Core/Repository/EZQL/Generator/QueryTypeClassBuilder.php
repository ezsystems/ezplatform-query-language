<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Generator;

use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\Core\QueryType\QueryType;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

final class QueryTypeClassBuilder
{
    /** @var \Zend\Code\Generator\ClassGenerator */
    private $class;

    public function __construct(string $className, string $classNamespace)
    {
        $this->class = new ClassGenerator($className, $classNamespace);
        $this->class->setImplementedInterfaces([QueryType::class]);
    }

    public function addGetNameMethod(string $queryName): self
    {
        $method = new MethodGenerator('getName');
        $method->setBody(sprintf('return "%s";', $queryName));
        $method->setReturnType('string');

        $this->class->addMethodFromGenerator($method);

        return $this;
    }

    public function addGetSupportedParametersMethod(iterable $parameters = []): self
    {
        $method = new MethodGenerator('getSupportedParameters');
        $method->setBody($this->createGetSupportedParametersMethodBody($parameters));
        $method->setReturnType('array');

        $this->class->addMethodFromGenerator($method);

        return $this;
    }

    public function addGetQueryMethod(string $body): self
    {
        $method = new MethodGenerator('getQuery');
        $method->setParameter(new ParameterGenerator('parameters', 'array', []));
        $method->setBody($body);
        $method->setReturnType(Query::class);

        $this->class->addMethodFromGenerator($method);

        return $this;
    }

    public function build(): ClassGenerator
    {
        return $this->class;
    }

    private function createGetSupportedParametersMethodBody(iterable $parameters): string
    {
        $names = [];
        foreach ($parameters as $parameter) {
            $names[] = sprintf('"%s"', $parameter);
        }

        return sprintf('return [%s];', implode(', ', $names));
    }
}
