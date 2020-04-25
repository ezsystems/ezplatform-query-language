<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Visitor;

use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Ancestor;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ContentId;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ContentTypeId;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ContentTypeIdentifier;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\DateMetadata;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\FieldRelation;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\IsFieldEmpty;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LanguageCode;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Location\Depth;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Location\IsMainLocation;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Location\Priority;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LocationId;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LocationRemoteId;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LogicalOr;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\MatchAll;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\MatchNone;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ObjectStateId;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ParentLocationId;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\RemoteId;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\SectionId;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Subtree;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\UserMetadata;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Visibility;
use EzSystems\EzPlatformQueryLanguage\Core\DataStructure\HashSet;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Generator\QueryTypeClassBuilder;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Generator\QueryTypeFileBuilder;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Generator\SymbolsTable;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\Context;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLBaseVisitor;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLParser;

final class QueryCompilerVisitor extends EZQLBaseVisitor
{
    /** @var \EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Generator\SymbolsTable */
    private $symbols;

    /** @var \EzSystems\EzPlatformQueryLanguage\Core\DataStructure\Set */
    private $supportedParameters;

    /** @var string */
    private $namespace;

    /** @var string */
    private $name;

    public function __construct(string $namespace, string $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->supportedParameters = new HashSet();
        $this->symbols = new SymbolsTable();
    }

    public function visitStmt(Context\StmtContext $context): string
    {
        $classBuilder = new QueryTypeClassBuilder($this->name, $this->namespace);
        $classBuilder->addGetQueryMethod($context->getChild(0)->accept($this));
        $classBuilder->addGetNameMethod($this->name);
        $classBuilder->addGetSupportedParametersMethod($this->supportedParameters);

        $fileBuilder = new QueryTypeFileBuilder($this->namespace);
        $fileBuilder->addSymbolsTable($this->symbols);
        $fileBuilder->addQueryTypeClass($classBuilder);

        return $fileBuilder->build()->generate();
    }

    public function visitSelectContent(Context\SelectContentContext $context): string
    {
        return $this->getQuery(Query::class, $context->properties->accept($this));
    }

    public function visitSelectContentInfo(Context\SelectContentInfoContext $context): string
    {
        return $this->getQuery(Query::class, $context->properties->accept($this));
    }

    public function visitSelectLocation(Context\SelectLocationContext $context): string
    {
        return $this->getQuery(LocationQuery::class, $context->properties->accept($this));
    }

    public function visitSelectProperties(Context\SelectPropertiesContext $context): array
    {
        $properties = [];
        if ($context->filter !== null) {
            $properties['filter'] = $context->filter->accept($this);
        }

        if ($context->query !== null) {
            $properties['query'] = $context->query->accept($this);
        }

        if ($context->limit !== null) {
            $properties['limit'] = $context->limit->accept($this);
        }

        if ($context->offset !== null) {
            $properties['offset'] = $context->offset->accept($this);
        }

        if ($context->sortClauses !== null) {
            $properties['sortClauses'] = $context->sortClauses->accept($this);
        }

        return $properties;
    }

    public function visitBoolean(Context\BooleanContext $context): string
    {
        switch ($context->val->getType()) {
            case EZQLParser::K_TRUE:
                return 'true';
            case EZQLParser::K_FALSE:
                return 'false';
        }
    }

    public function visitInt(Context\IntContext $context): string
    {
        return var_export((int)$context->val->getText(), true);
    }

    public function visitDouble(Context\DoubleContext $context): string
    {
        return var_export((float)$context->val->getText(), true);
    }

    public function visitString(Context\StringContext $context): string
    {
        return var_export($context->val->getText(), true);
    }

    public function visitParameter(Context\ParameterContext $context): string
    {
        // Remove ':' prefix from parameter name
        $name = substr($context->name->getText(), 1);
        $this->supportedParameters->add($name);

        return sprintf('$parameters["%s"]', $name);
    }

    public function visitArgument(Context\ArgumentContext $context): string
    {
        return $context->getChild(0)->accept($this);
    }

    public function visitArgumentList(Context\ArgumentListContext $context): string
    {
        $args = [];
        foreach ($context->argument() as $arg) {
            $args[] = $arg->accept($this);
        }

        return implode(', ', $args);
    }

    public function visitArgumentRange(Context\ArgumentRangeContext $context): string
    {
        return sprintf(
            '[%s, %s]',
            $context->a->accept($this),
            $context->b->accept($this)
        );
    }

    public function visitBuildInOperator(Context\BuildInOperatorContext $context)
    {
        switch ($context->op->getType()) {
            case EZQLParser::EQ:
                return $this->getConstReference(Operator::class, 'EQ');
            case EZQLParser::K_IN:
                return $this->getConstReference(Operator::class, 'IN');
            case EZQLParser::GT:
                return $this->getConstReference(Operator::class, 'GT');
            case EZQLParser::GTE:
                return $this->getConstReference(Operator::class, 'GTE');
            case EZQLParser::LT:
                return $this->getConstReference(Operator::class, 'LT');
            case EZQLParser::LTE:
                return $this->getConstReference(Operator::class, 'LTE');
            case EZQLParser::K_BETWEEN:
                return $this->getConstReference(Operator::class, 'BETWEEN');
            case EZQLParser::K_LIKE:
                return $this->getConstReference(Operator::class, 'LIKE');
            case EZQLParser::K_CONTAINS:
                return $this->getConstReference(Operator::class, 'CONTAINS');
        }
    }

    public function visitNotEQ(Context\NotEQContext $context)
    {
        throw new \RuntimeException('TODO: Implement visitNotEQ');
    }

    public function visitNotInOperator(Context\NotInOperatorContext $context)
    {
        throw new \RuntimeException('TODO: Implement visitNotInOperator');
    }

    public function visitMatchAllExpr(Context\MatchAllExprContext $context): string
    {
        return $this->getCriterion(MatchAll::class);
    }

    public function visitMatchNoneExpr(Context\MatchNoneExprContext $context): string
    {
        return $this->getCriterion(MatchNone::class);
    }

    public function visitIsMainLocationExpr(Context\IsMainLocationExprContext $context)
    {
        return $this->getCriterion(IsMainLocation::class, [
            $this->getConstReference(IsMainLocation::class, 'MAIN'),
        ]);
    }

    public function visitIsNotMainLocationExpr(Context\IsNotMainLocationExprContext $context): string
    {
        return $this->getCriterion(IsMainLocation::class, [
            $this->getConstReference(IsMainLocation::class, 'NOT_MAIN'),
        ]);
    }

    public function visitCreatedExpr(Context\CreatedExprContext $context): string
    {
        return $this->getCriterion(DateMetadata::class, [
            $this->getConstReference(DateMetadata::class, 'CREATED'),
            $context->op->accept($this),
            $context->val->accept($this),
        ]);
    }

    public function visitModifiedExpr(Context\ModifiedExprContext $context): string
    {
        return $this->getCriterion(DateMetadata::class, [
            $this->getConstReference(DateMetadata::class, 'MODIFIED'),
            $context->op->accept($this),
            $context->val->accept($this),
        ]);
    }

    public function visitIsFieldEmptyExpr(Context\IsFieldEmptyExprContext $context): string
    {
        return $this->getCriterion(IsFieldEmpty::class, [
            var_export($context->field->getText(), true),
            var_export(true, true),
        ]);
    }

    public function visitIsNotFieldEmptyExpr(Context\IsNotFieldEmptyExprContext $context): string
    {
        return $this->getCriterion(IsFieldEmpty::class, [
            var_export($context->field->getText(), true),
            var_export(false, true),
        ]);
    }

    public function visitFieldRelationExpr(Context\FieldRelationExprContext $context): string
    {
        return $this->getCriterion(FieldRelation::class, [
            var_export($context->field->getText(), true),
            $context->op->accept($this),
            $context->val->accept($this),
        ]);
    }

    public function visitLocationPriorityExpr(Context\LocationPriorityExprContext $context): string
    {
        return $this->getCriterion(Priority::class, [
            $context->op->accept($this),
            $context->val->accept($this),
        ]);
    }

    public function visitLocationDepthExpr(Context\LocationDepthExprContext $context)
    {
        return $this->getCriterion(Depth::class, [
            $context->op->accept($this),
            $context->val->accept($this),
        ]);
    }

    public function visitVisibilityExpr(Context\VisibilityExprContext $context): string
    {
        switch ($context->flag->getType()) {
            case EZQLParser::K_VISIBLE:
                return $this->getCriterion(Visibility::class, [
                    $this->getConstReference(Visibility::class, 'VISIBLE'),
                ]);
            case EZQLParser::K_HIDDEN:
                return $this->getCriterion(Visibility::class, [
                    $this->getConstReference(Visibility::class, 'HIDDEN'),
                ]);
        }
    }

    public function visitUserMetadataExpr(Context\UserMetadataExprContext $context): string
    {
        return $this->getCriterion(Criterion\UserMetadata::class, [
            $context->target->accept($this),
            $context->op->accept($this),
            $context->val->accept($this),
        ]);
    }

    public function visitUserMetadataTarget(Context\UserMetadataTargetContext $context): string
    {
        switch ($context->target->getType()) {
            case EZQLParser::K_OWNER:
                return $this->getConstReference(UserMetadata::class, 'OWNER');
            case EZQLParser::K_GROUP:
                return $this->getConstReference(UserMetadata::class, 'GROUP');
            case EZQLParser::K_MODIFIER:
                return $this->getConstReference(UserMetadata::class, 'MODIFIER');
        }
    }

    public function visitAncestorExpr(Context\AncestorExprContext $context): string
    {
        return $this->getCriterion(Ancestor::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitContentIdExpr(Context\ContentIdExprContext $context): string
    {
        return $this->getCriterion(ContentId::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitContentTypeIdExpr(Context\ContentTypeIdExprContext $context): string
    {
        return $this->getCriterion(ContentTypeId::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitContentTypeIdentifierExpr(Context\ContentTypeIdentifierExprContext $context): string
    {
        return $this->getCriterion(ContentTypeIdentifier::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitLanguageCodeExpr(Context\LanguageCodeExprContext $context): string
    {
        return $this->getCriterion(LanguageCode::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitLocationIdExpr(Context\LocationIdExprContext $context): string
    {
        return $this->getCriterion(LocationId::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitLocationRemoteIdExpr(Context\LocationRemoteIdExprContext $context): string
    {
        return $this->getCriterion(LocationRemoteId::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitObjectStateIdExpr(Context\ObjectStateIdExprContext $context): string
    {
        return $this->getCriterion(ObjectStateId::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitParentLocationIdExpr(Context\ParentLocationIdExprContext $context): string
    {
        return $this->getCriterion(ParentLocationId::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitRemoteIdExpr(Context\RemoteIdExprContext $context): string
    {
        return $this->getCriterion(RemoteId::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitSectionIdExpr(Context\SectionIdExprContext $context): string
    {
        return $this->getCriterion(SectionId::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitSubtreeExpr(Context\SubtreeExprContext $context): string
    {
        return $this->getCriterion(Subtree::class, [
            $context->val->accept($this),
        ]);
    }

    public function visitOrExpr(Context\OrExprContext $context): string
    {
        return $this->getCriterion(LogicalOr::class, [
            $context->left->accept($this),
            $context->right->accept($this),
        ]);
    }

    public function visitAndExpr(Context\AndExprContext $context): string
    {
        return $this->getCriterion(Criterion\LogicalAnd::class, [
            $context->left->accept($this),
            $context->right->accept($this),
        ]);
    }

    public function visitInnerExpr(Context\InnerExprContext $context): string
    {
        return $context->expr()->accept($this);
    }

    public function visitSortClauseList(Context\SortClauseListContext $context): string
    {
        $lines = [];
        $lines[] = '[';
        foreach ($context->sortClause() as $sortClause) {
            $lines[] = "\t" . $sortClause->accept($this) . ',';
        }
        $lines[] = ']';

        return implode(PHP_EOL, $lines);
    }

    public function visitSortClause(Context\SortClauseContext $context): string
    {
        $class = $context->name->getText();
        if (strpos($class, '\\') !== 0) {
            $class = 'eZ\\Publish\\API\\Repository\\Values\\Content\\Query\\SortClause\\' . $class;
        }

        return $this->getCriterion(
            $class,
            [
                $context->sortOrder()->accept($this),
            ]
        );
    }

    public function visitSortOrder(Context\SortOrderContext $context): string
    {
        switch ($context->order->getType()) {
            case EZQLParser::K_ASC:
                return $this->getConstReference(Query::class, 'SORT_ASC');
            case EZQLParser::K_DESC:
                return $this->getConstReference(Query::class, 'SORT_DESC');
        }
    }

    public function getCriterion(string $class, array $args = []): string
    {
        $class = $this->symbols->getLocalName($class);

        if (count($args) === 0) {
            return sprintf('new %s()', $class);
        } elseif (count($args) === 1) {
            return sprintf('new %s(%s)', $class, $args[0]);
        } else {
            $lines = [];
            $lines[] = "new $class(";
            foreach ($args as $idx => $arg) {
                $line = "\t$arg";
                if ($idx + 1 < count($args)) {
                    $line .= ',';
                }

                $lines[] = $line;
            }

            $lines[] = ')';

            return implode(PHP_EOL, $lines);
        }
    }

    private function getQuery(string $queryClass, array $properties): string
    {
        $queryClass = $this->symbols->getLocalName($queryClass);

        $lines = [];
        $lines[] = sprintf('$query = new %s();', $queryClass);
        foreach ($properties as $name => $value) {
            $lines[] = sprintf('$query->%s = %s;', $name, $value);
        }

        $lines[] = '';
        $lines[] = 'return $query;';

        return implode(PHP_EOL, $lines);
    }

    private function getConstReference(string $class, string $name): string
    {
        return $this->symbols->getLocalName($class) . '::' . $name;
    }
}
