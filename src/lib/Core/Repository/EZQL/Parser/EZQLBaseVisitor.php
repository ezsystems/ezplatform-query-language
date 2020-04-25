<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser;

use Antlr\Antlr4\Runtime\Tree\AbstractParseTreeVisitor;

/**
 * This class provides an empty implementation of {@see EZQLVisitor},
 * which can be extended to create a visitor which only needs to handle a subset
 * of the available methods.
 */
class EZQLBaseVisitor extends AbstractParseTreeVisitor implements EZQLVisitor
{
    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitStmt(Context\StmtContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitSelectLocation(Context\SelectLocationContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitSelectContent(Context\SelectContentContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitSelectContentInfo(Context\SelectContentInfoContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitSelectProperties(Context\SelectPropertiesContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitSortClauseList(Context\SortClauseListContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitSortClause(Context\SortClauseContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitSortOrder(Context\SortOrderContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitIsFieldEmptyExpr(Context\IsFieldEmptyExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitFieldRelationExpr(Context\FieldRelationExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitLocationRemoteIdExpr(Context\LocationRemoteIdExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitVisibilityExpr(Context\VisibilityExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitContentIdExpr(Context\ContentIdExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitRemoteIdExpr(Context\RemoteIdExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitObjectStateIdExpr(Context\ObjectStateIdExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitLanguageCodeExpr(Context\LanguageCodeExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitIsMainLocationExpr(Context\IsMainLocationExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitLocationPriorityExpr(Context\LocationPriorityExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitContentTypeIdentifierExpr(Context\ContentTypeIdentifierExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitLocationIdExpr(Context\LocationIdExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitFulltextExpr(Context\FulltextExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitParentLocationIdExpr(Context\ParentLocationIdExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitCriterionExpr(Context\CriterionExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitUserMetadataExpr(Context\UserMetadataExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitMatchAllExpr(Context\MatchAllExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitLocationDepthExpr(Context\LocationDepthExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitOrExpr(Context\OrExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitIsNotMainLocationExpr(Context\IsNotMainLocationExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitInnerExpr(Context\InnerExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitSubtreeExpr(Context\SubtreeExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitFieldExpr(Context\FieldExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitModifiedExpr(Context\ModifiedExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitAncestorExpr(Context\AncestorExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitMatchNoneExpr(Context\MatchNoneExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitIsNotFieldEmptyExpr(Context\IsNotFieldEmptyExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitSectionIdExpr(Context\SectionIdExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitCreatedExpr(Context\CreatedExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitContentTypeIdExpr(Context\ContentTypeIdExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitAndExpr(Context\AndExprContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitBuildInOperator(Context\BuildInOperatorContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitNotInOperator(Context\NotInOperatorContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitNotEQ(Context\NotEQContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitFuzziness(Context\FuzzinessContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitBoosting(Context\BoostingContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitFieldBoost(Context\FieldBoostContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitValue(Context\ValueContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitArgumentRange(Context\ArgumentRangeContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitArgumentList(Context\ArgumentListContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitArgument(Context\ArgumentContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitParameter(Context\ParameterContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitInt(Context\IntContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitBoolean(Context\BooleanContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitString(Context\StringContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitDouble(Context\DoubleContext $context)
    {
        return $this->visitChildren($context);
    }

    /**
     * {@inheritdoc}
     *
     * The default implementation returns the result of calling
     * {@see self::visitChildren()} on `context`.
     */
    public function visitUserMetadataTarget(Context\UserMetadataTargetContext $context)
    {
        return $this->visitChildren($context);
    }
}
