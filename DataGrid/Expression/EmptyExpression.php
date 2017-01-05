<?php

namespace Bilendi\DevExpressBundle\DataGrid\Expression;

use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;

/**
 * Class EmptyExpression.
 */
class EmptyExpression implements Visitable
{
    /**
     * @param AbstractExpressionVisitor $visitor
     *
     * @return mixed
     */
    public function visit(AbstractExpressionVisitor $visitor)
    {
        return $visitor->visitEmpty();
    }
}
