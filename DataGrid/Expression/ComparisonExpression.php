<?php

namespace Bilendi\DevExpressBundle\DataGrid\Expression;


use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;

/**
 * Class ComparisonExpression
 * @package Bilendi\DevExpressBundle\DataGrid\Expression
 */
class ComparisonExpression implements Visitable
{
    const EQ = '=';
    const NE = '<>';
    const LT = '<';
    const LE = '<=';
    const GT = '>';
    const GE = '>=';
    const CONTAINS = 'contains';
    const NOTCONTAINS = 'notcontains';
    const STARTSWITH = 'startswith';
    const ENDSWITH = 'endswith';

    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @var
     */
    protected $value;

    /**
     * ComparisonExpression constructor.
     * @param string $field
     * @param string $operator
     * @param $value
     */
    public function __construct(string $field, string $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }


    /**
     * @param AbstractExpressionVisitor $visitor
     * @return mixed
     */
    function visit(AbstractExpressionVisitor $visitor)
    {
        return $visitor->visitComparison($this);
    }

}