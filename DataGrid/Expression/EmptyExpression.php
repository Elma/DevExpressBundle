<?php

namespace Bilendi\DevExpressBundle\DataGrid\Expression;

use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;

/**
 * Class EmptyExpression.
 */
class EmptyExpression implements Visitable
{
    /**
     * @return mixed
     */
    public function visit(AbstractExpressionVisitor $visitor)
    {
        return $visitor->visitEmpty();
    }
}
