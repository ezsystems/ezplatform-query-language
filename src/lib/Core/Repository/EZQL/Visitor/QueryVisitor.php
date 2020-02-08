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
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\DateMetadata;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Field;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\FieldRelation;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\IsFieldEmpty;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Location\IsMainLocation;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Location\Priority;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LogicalNot;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\MatchAll;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\MatchNone;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\UserMetadata;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Visibility;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\CriterionBinding\CriterionBinding;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Exception\MissingParameterException;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\EZQLOperator;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\Context;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLBaseVisitor;
use EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser\EZQLParser;
use RuntimeException;

final class QueryVisitor extends EZQLBaseVisitor
{
    private const BUILD_IN_CRITERION_NAMESPACE = 'eZ\\Publish\\API\\Repository\\Values\\Content\Query\\Criterion\\';

    /** @var array */
    private $parameters;

    /** @var array<string,CriterionBinding> */
    private $criterions;

    public function __construct(array $criterions, array $parameters = [])
    {
        $this->parameters = $parameters;
        $this->criterions = $criterions;
    }

    public function visitSelectContent(Context\SelectContentContext $context): QueryVisitorResult
    {
        return new QueryVisitorResult(
            QueryVisitorResult::TARGET_CONTENT,
            new Query($context->properties->accept($this))
        );
    }

    public function visitSelectContentInfo(Context\SelectContentInfoContext $context): QueryVisitorResult
    {
        return new QueryVisitorResult(
            QueryVisitorResult::TARGET_CONTENT_INFO,
            new Query($context->properties->accept($this))
        );
    }

    public function visitSelectLocation(Context\SelectLocationContext $context): QueryVisitorResult
    {
        return new QueryVisitorResult(
            QueryVisitorResult::TARGET_LOCATION,
            new LocationQuery($context->properties->accept($this))
        );
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

    public function visitBoolean(Context\BooleanContext $context): bool
    {
        switch ($context->val->getType()) {
            case EZQLParser::K_TRUE:
                return true;
            case EZQLParser::K_FALSE:
                return false;
        }
    }

    public function visitInt(Context\IntContext $context): int
    {
        return (int)$context->val->getText();
    }

    public function visitDouble(Context\DoubleContext $context): float
    {
        return (float)$context->val->getText();
    }

    public function visitString(Context\StringContext $context): string
    {
        // Remove " prefix and affix from string value
        return substr($context->val->getText(), 1, -1);
    }

    public function visitParameter(Context\ParameterContext $context)
    {
        // Remove ':' prefix from parameter name
        $name = substr($context->name->getText(), 1);

        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        throw new MissingParameterException($name);
    }

    public function visitArgument(Context\ArgumentContext $context)
    {
        return $context->getChild(0)->accept($this);
    }

    public function visitArgumentList(Context\ArgumentListContext $context): array
    {
        $args = [];
        foreach ($context->argument() as $arg) {
            $args[] = $arg->accept($this);
        }

        return $args;
    }

    public function visitArgumentRange(Context\ArgumentRangeContext $context): array
    {
        return [
            $context->a->accept($this),
            $context->b->accept($this),
        ];
    }

    public function visitBuildInOperator(Context\BuildInOperatorContext $context)
    {
        switch ($context->op->getType()) {
            case EZQLParser::EQ:
                return EZQLOperator::EQ;
            case EZQLParser::K_IN:
                return EZQLOperator::IN;
            case EZQLParser::GT:
                return EZQLOperator::GT;
            case EZQLParser::GTE:
                return EZQLOperator::GTE;
            case EZQLParser::LT:
                return EZQLOperator::LT;
            case EZQLParser::LTE:
                return EZQLOperator::LTE;
            case EZQLParser::K_BETWEEN:
                return EZQLOperator::BETWEEN;
            case EZQLParser::K_LIKE:
                return EZQLOperator::LIKE;
            case EZQLParser::K_CONTAINS:
                return EZQLOperator::CONTAINS;
        }
    }

    public function visitNotEQ(Context\NotEQContext $context): string
    {
        return EZQLOperator::NEQ;
    }

    public function visitNotInOperator(Context\NotInOperatorContext $context): string
    {
        return EZQLOperator::NOT_IN;
    }

    public function visitMatchAllExpr(Context\MatchAllExprContext $context): Criterion
    {
        return new MatchAll();
    }

    public function visitMatchNoneExpr(Context\MatchNoneExprContext $context): Criterion
    {
        return new MatchNone();
    }

    public function visitIsMainLocationExpr(Context\IsMainLocationExprContext $context): IsMainLocation
    {
        return new IsMainLocation(IsMainLocation::MAIN);
    }

    public function visitIsNotMainLocationExpr(Context\IsNotMainLocationExprContext $context): IsMainLocation
    {
        return new IsMainLocation(IsMainLocation::NOT_MAIN);
    }

    public function visitCreatedExpr(Context\CreatedExprContext $context): Criterion
    {
        $val = $context->val->accept($this);

        return $this->resolveNegation(
            static function (string $op) use ($val): Criterion {
                return new DateMetadata(DateMetadata::CREATED, $op, $val);
            },
            $context->op->accept($this)
        );
    }

    public function visitModifiedExpr(Context\ModifiedExprContext $context): Criterion
    {
        $val = $context->val->accept($this);

        return $this->resolveNegation(
            static function (string $op) use ($val): Criterion {
                return new DateMetadata(DateMetadata::MODIFIED, $op, $val);
            },
            $context->op->accept($this)
        );
    }

    public function visitIsFieldEmptyExpr(Context\IsFieldEmptyExprContext $context): Criterion
    {
        return new IsFieldEmpty($context->field->getText(), true);
    }

    public function visitIsNotFieldEmptyExpr(Context\IsNotFieldEmptyExprContext $context): Criterion
    {
        return new IsFieldEmpty($context->field->getText(), false);
    }

    public function visitFieldExpr(Context\FieldExprContext $context): Criterion
    {
        $field = $context->field->getText();
        $value = $context->val->accept($this);

        return $this->resolveNegation(
            static function (string $op) use ($field, $value): Criterion {
                return new Field($field, $op, $value);
            },
            $context->op->accept($this)
        );
    }

    public function visitFieldRelationExpr(Context\FieldRelationExprContext $context): Criterion
    {
        $field = $context->field->getText();
        $value = $context->val->accept($this);

        return $this->resolveNegation(
            static function (string $op) use ($field, $value): Criterion {
                return new FieldRelation($field, $op, $value);
            },
            $context->op->accept($this)
        );
    }

    public function visitLocationPriorityExpr(Context\LocationPriorityExprContext $context): Criterion
    {
        $value = $context->val->accept($this);

        return $this->resolveNegation(
            static function (string $op) use ($value): Criterion {
                return new Priority($op, $value);
            },
            $context->op->accept($this)
        );
    }

    public function visitVisibilityExpr(Context\VisibilityExprContext $context): Criterion
    {
        switch ($context->flag->getType()) {
            case EZQLParser::K_VISIBLE:
                return new Visibility(Visibility::VISIBLE);
            case EZQLParser::K_HIDDEN:
                return new Visibility(Visibility::HIDDEN);
        }
    }

    public function visitUserMetadataExpr(Context\UserMetadataExprContext $context): Criterion
    {
        $target = $context->target->accept($this);
        $value = $context->val->accept($this);

        return $this->resolveNegation(
            static function (string $op) use ($target, $value): Criterion {
                return new UserMetadata($target, $op, $value);
            },
            $context->op->accept($this)
        );
    }

    public function visitUserMetadataTarget(Context\UserMetadataTargetContext $context): string
    {
        switch ($context->target->getType()) {
            case EZQLParser::K_OWNER:
                return UserMetadata::OWNER;
            case EZQLParser::K_GROUP:
                return UserMetadata::GROUP;
            case EZQLParser::K_MODIFIER:
                return UserMetadata::MODIFIER;
        }
    }

    public function visitFulltextExpr(Context\FulltextExprContext $context): Criterion
    {
        $options = [];
        if ($context->fuzziness() !== null) {
            $options['fuzziness'] = $context->fuzziness()->accept($this);
        }

        if ($context->boosting() !== null) {
            $options['boost'] = $context->boosting()->accept($this);
        }

        return new Criterion\FullText($context->val->accept($this), $options);
    }

    public function visitBoosting(Context\BoostingContext $context): array
    {
        $boosting = [];
        foreach ($context->fieldBoost() as $fieldBoost) {
            [$field, $boostFactor] = $fieldBoost->accept($this);

            $boosting[$field] = $boostFactor;
        }

        return $boosting;
    }

    public function visitFieldBoost(Context\FieldBoostContext $context): array
    {
        return [
            $context->field->getText(),
            $context->val->accept($this),
        ];
    }

    public function visitCriterionExpr(Context\CriterionExprContext $context): Criterion
    {
        $class = $context->clazz->getText();
        $value = $context->value()->accept($this);

        return $this->resolveNegation(
            function (string $op) use ($class, $value): Criterion {
                return $this->createCriterion($class, $op, $value);
            },
            $context->op->accept($this)
        );
    }

    public function visitAndExpr(Context\AndExprContext $context): Query\Criterion\LogicalAnd
    {
        return new Query\Criterion\LogicalAnd([
            $context->left->accept($this),
            $context->right->accept($this),
        ]);
    }

    public function visitOrExpr(Context\OrExprContext $context): Query\Criterion\LogicalOr
    {
        return new Query\Criterion\LogicalOr([
            $context->left->accept($this),
            $context->right->accept($this),
        ]);
    }

    public function visitInnerExpr(Context\InnerExprContext $context): Criterion
    {
        return $context->expr()->accept($this);
    }

    public function visitSortClauseList(Context\SortClauseListContext $context): array
    {
        $sortClauses = [];
        foreach ($context->sortClause() as $sortClause) {
            $sortClauses[] = $sortClause->accept($this);
        }

        return $sortClauses;
    }

    public function visitSortClause(Context\SortClauseContext $context): Query\SortClause
    {
        $class = $context->name->getText();
        $direction = $context->sortOrder()->accept($this);

        if (strpos($class, '\\') !== 0) {
            $class = 'eZ\\Publish\\API\\Repository\\Values\\Content\\Query\\SortClause\\' . $class;
        }

        $sortClause = new $class($direction);
        if (!($sortClause instanceof Query\SortClause)) {
            throw new RuntimeException("$class must implement " . Query\SortClause::class);
        }

        return $sortClause;
    }

    public function visitSortOrder(Context\SortOrderContext $context): string
    {
        switch ($context->order->getType()) {
            case EZQLParser::K_ASC:
                return Query::SORT_ASC;
            case EZQLParser::K_DESC:
                return Query::SORT_DESC;
        }
    }

    private function resolveNegation(callable $createCriterion, string $op): Criterion
    {
        $isNegation = false;

        switch ($op) {
            case EZQLOperator::NEQ:
                $op = EZQLOperator::EQ;
                $isNegation = true;
                break;
            case EZQLOperator::NOT_IN:
                $op = EZQLOperator::IN;
                $isNegation = true;
                break;
        }

        /** @var \eZ\Publish\API\Repository\Values\Content\Query\Criterion $citerion */
        $criterion = $createCriterion($op);
        if ($isNegation) {
            $criterion = new LogicalNot($criterion);
        }

        return $criterion;
    }

    private function createCriterion(string $class, string $operator, $value, array $attributes = []): Criterion
    {
        if (strpos($class, '\\') === false) {
            $class = self::BUILD_IN_CRITERION_NAMESPACE . $class;
        }

        return $this->criterions[$class]->instantiate($operator, $value, $attributes);
    }
}
