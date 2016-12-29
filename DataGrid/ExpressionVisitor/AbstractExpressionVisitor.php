<?php

namespace Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor;


use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\Visitable;

/**
 * Class AbstractExpressionVisitor
 * @package Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor
 */
abstract class AbstractExpressionVisitor
{
    /**
     * @param ComparisonExpression $comparison
     * @return mixed
     */
    abstract function visitComparison(ComparisonExpression $comparison);

    /**
     * @param $type
     * @param array $expressions
     * @return mixed
     */
    abstract function visitProcessedCompositeExpression(string $type, array $expressions);

    /**
     * @param CompositeExpression $compositeExpression
     * @return mixed
     */
    public function visitCompositeExpression(CompositeExpression $compositeExpression) {
        $expressions = \Functional\select($compositeExpression->getExpressions(), function (Visitable $expr) {
            return $expr->visit($this);
        });
        return $this->visitProcessedCompositeExpression($compositeExpression->getType(), $expressions);
    }

    /**
     * @return null
     */
    function visitEmpty() {
        return null;
    }
}