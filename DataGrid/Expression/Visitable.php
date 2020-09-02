<?php

namespace Bilendi\DevExpressBundle\DataGrid\Expression;

use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;

/**
 * Interface Visitable.
 */
interface Visitable
{
    /**
     * @return mixed
     */
    public function visit(AbstractExpressionVisitor $visitor);
}
