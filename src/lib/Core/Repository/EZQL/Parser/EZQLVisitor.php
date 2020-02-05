<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformQueryLanguage\Core\Repository\EZQL\Parser;

use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;

/**
 * This interface defines a complete generic visitor for a parse tree produced by {@see EZQLParser}.
 */
interface EZQLVisitor extends ParseTreeVisitor
{
    /**
     * Visit a parse tree produced by {@see EZQLParser::stmt()}.
     *
     * @param Context\StmtContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitStmt(Context\StmtContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::selectLocation()}.
     *
     * @param Context\SelectLocationContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitSelectLocation(Context\SelectLocationContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::selectContent()}.
     *
     * @param Context\SelectContentContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitSelectContent(Context\SelectContentContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::selectContentInfo()}.
     *
     * @param Context\SelectContentInfoContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitSelectContentInfo(Context\SelectContentInfoContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::selectProperties()}.
     *
     * @param Context\SelectPropertiesContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitSelectProperties(Context\SelectPropertiesContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::sortClauseList()}.
     *
     * @param Context\SortClauseListContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitSortClauseList(Context\SortClauseListContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::sortClause()}.
     *
     * @param Context\SortClauseContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitSortClause(Context\SortClauseContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::sortOrder()}.
     *
     * @param Context\SortOrderContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitSortOrder(Context\SortOrderContext $context);

    /**
     * Visit a parse tree produced by the `userMetadataExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\UserMetadataExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitUserMetadataExpr(Context\UserMetadataExprContext $context);

    /**
     * Visit a parse tree produced by the `isFieldEmptyExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\IsFieldEmptyExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitIsFieldEmptyExpr(Context\IsFieldEmptyExprContext $context);

    /**
     * Visit a parse tree produced by the `fieldRelationExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\FieldRelationExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitFieldRelationExpr(Context\FieldRelationExprContext $context);

    /**
     * Visit a parse tree produced by the `matchAllExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\MatchAllExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitMatchAllExpr(Context\MatchAllExprContext $context);

    /**
     * Visit a parse tree produced by the `orExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\OrExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitOrExpr(Context\OrExprContext $context);

    /**
     * Visit a parse tree produced by the `isNotMainLocationExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\IsNotMainLocationExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitIsNotMainLocationExpr(Context\IsNotMainLocationExprContext $context);

    /**
     * Visit a parse tree produced by the `innerExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\InnerExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitInnerExpr(Context\InnerExprContext $context);

    /**
     * Visit a parse tree produced by the `visibilityExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\VisibilityExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitVisibilityExpr(Context\VisibilityExprContext $context);

    /**
     * Visit a parse tree produced by the `fieldExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\FieldExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitFieldExpr(Context\FieldExprContext $context);

    /**
     * Visit a parse tree produced by the `modifiedExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\ModifiedExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitModifiedExpr(Context\ModifiedExprContext $context);

    /**
     * Visit a parse tree produced by the `matchNoneExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\MatchNoneExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitMatchNoneExpr(Context\MatchNoneExprContext $context);

    /**
     * Visit a parse tree produced by the `isNotFieldEmptyExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\IsNotFieldEmptyExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitIsNotFieldEmptyExpr(Context\IsNotFieldEmptyExprContext $context);

    /**
     * Visit a parse tree produced by the `createdExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\CreatedExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitCreatedExpr(Context\CreatedExprContext $context);

    /**
     * Visit a parse tree produced by the `isMainLocationExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\IsMainLocationExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitIsMainLocationExpr(Context\IsMainLocationExprContext $context);

    /**
     * Visit a parse tree produced by the `locationPriorityExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\LocationPriorityExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitLocationPriorityExpr(Context\LocationPriorityExprContext $context);

    /**
     * Visit a parse tree produced by the `fulltextExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\FulltextExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitFulltextExpr(Context\FulltextExprContext $context);

    /**
     * Visit a parse tree produced by the `criterionExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\CriterionExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitCriterionExpr(Context\CriterionExprContext $context);

    /**
     * Visit a parse tree produced by the `andExpr` labeled alternative
     * in {@see EZQLParser::expr()}.
     *
     * @param Context\AndExprContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitAndExpr(Context\AndExprContext $context);

    /**
     * Visit a parse tree produced by the `buildInOperator` labeled alternative
     * in {@see EZQLParser::operator()}.
     *
     * @param Context\BuildInOperatorContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitBuildInOperator(Context\BuildInOperatorContext $context);

    /**
     * Visit a parse tree produced by the `notInOperator` labeled alternative
     * in {@see EZQLParser::operator()}.
     *
     * @param Context\NotInOperatorContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitNotInOperator(Context\NotInOperatorContext $context);

    /**
     * Visit a parse tree produced by the `notEQ` labeled alternative
     * in {@see EZQLParser::operator()}.
     *
     * @param Context\NotEQContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitNotEQ(Context\NotEQContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::fuzziness()}.
     *
     * @param Context\FuzzinessContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitFuzziness(Context\FuzzinessContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::boosting()}.
     *
     * @param Context\BoostingContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitBoosting(Context\BoostingContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::fieldBoost()}.
     *
     * @param Context\FieldBoostContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitFieldBoost(Context\FieldBoostContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::value()}.
     *
     * @param Context\ValueContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitValue(Context\ValueContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::argumentRange()}.
     *
     * @param Context\ArgumentRangeContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitArgumentRange(Context\ArgumentRangeContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::argumentList()}.
     *
     * @param Context\ArgumentListContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitArgumentList(Context\ArgumentListContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::argument()}.
     *
     * @param Context\ArgumentContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitArgument(Context\ArgumentContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::parameter()}.
     *
     * @param Context\ParameterContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitParameter(Context\ParameterContext $context);

    /**
     * Visit a parse tree produced by the `int` labeled alternative
     * in {@see EZQLParser::scalar()}.
     *
     * @param Context\IntContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitInt(Context\IntContext $context);

    /**
     * Visit a parse tree produced by the `boolean` labeled alternative
     * in {@see EZQLParser::scalar()}.
     *
     * @param Context\BooleanContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitBoolean(Context\BooleanContext $context);

    /**
     * Visit a parse tree produced by the `string` labeled alternative
     * in {@see EZQLParser::scalar()}.
     *
     * @param Context\StringContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitString(Context\StringContext $context);

    /**
     * Visit a parse tree produced by the `double` labeled alternative
     * in {@see EZQLParser::scalar()}.
     *
     * @param Context\DoubleContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitDouble(Context\DoubleContext $context);

    /**
     * Visit a parse tree produced by {@see EZQLParser::userMetadataTarget()}.
     *
     * @param Context\UserMetadataTargetContext $context The parse tree.
     *
     * @return mixed The visitor result.
     */
    public function visitUserMetadataTarget(Context\UserMetadataTargetContext $context);
}
