<?php

namespace Bilendi\DevExpressBundle\DataGrid\Expression;

use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;

/**
 * Class CompositeExpression.
 */
class CompositeExpression implements Visitable
{
    const TYPE_AND = 'and';
    const TYPE_OR = 'or';

    /**
     * @var string
     */
    private $type;

    /**
     * @var Visitable[]
     */
    private $expressions = [];

    /**
     * @param string      $type
     * @param Visitable[] $expressions
     *
     * @throws \RuntimeException
     */
    public function __construct($type, array $expressions)
    {
        $this->type = $type;
        $this->expressions = $expressions;
    }

    /**
     * Returns the list of expressions nested in this composite.
     *
     * @return Visitable[]
     */
    public function getExpressions(): array
    {
        return $this->expressions;
    }

    /**
     * @param Visitable $expression
     *
     * @return $this
     */
    public function addExpression(Visitable $expression)
    {
        $this->expressions[] = $expression;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function visit(AbstractExpressionVisitor $visitor)
    {
        return $visitor->visitCompositeExpression($this);
    }
}
