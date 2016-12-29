<?php

namespace Bilendi\DevExpressBundle\DataGrid\Expression;


use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;

/**
 * Interface Visitable
 * @package Bilendi\DevExpressBundle\DataGrid\Expression
 */
interface Visitable
{
    /**
     * @param AbstractExpressionVisitor $visitor
     * @return mixed
     */
    function visit(AbstractExpressionVisitor $visitor);
}