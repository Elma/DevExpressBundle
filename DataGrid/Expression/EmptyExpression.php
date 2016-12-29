<?php

namespace Bilendi\DevExpressBundle\DataGrid\Expression;


use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;

/**
 * Class EmptyExpression
 * @package Bilendi\DevExpressBundle\DataGrid\Expression
 */
class EmptyExpression implements Visitable
{
    /**
     * @param AbstractExpressionVisitor $visitor
     * @return mixed
     */
    function visit(AbstractExpressionVisitor $visitor)
    {
        return $visitor->visitEmpty();
    }
}