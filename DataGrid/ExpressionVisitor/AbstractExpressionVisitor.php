<?php

namespace Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor;

use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\Visitable;

/**
 * Class AbstractExpressionVisitor.
 */
abstract class AbstractExpressionVisitor
{
    /**
     * @return mixed
     */
    abstract public function visitComparison(ComparisonExpression $comparison);

    /**
     * @param $type
     *
     * @return mixed
     */
    abstract public function visitProcessedCompositeExpression(string $type, array $expressions);

    /**
     * @return mixed
     */
    public function visitCompositeExpression(CompositeExpression $compositeExpression)
    {
        $expressions = \Functional\map($compositeExpression->getExpressions(), function (Visitable $expr) {
            return $expr->visit($this);
        });
        $expressions = \Functional\filter($expressions, function ($expr) {
            return null !== $expr;
        });

        return $this->visitProcessedCompositeExpression($compositeExpression->getType(), $expressions);
    }

    public function visitEmpty()
    {
        return;
    }
}
