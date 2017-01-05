<?php

namespace Bilendi\DevExpressBundle\DataGrid\Expression;

use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;

/**
 * Interface Visitable.
 */
interface Visitable
{
    /**
     * @param AbstractExpressionVisitor $visitor
     *
     * @return mixed
     */
    public function visit(AbstractExpressionVisitor $visitor);
}
