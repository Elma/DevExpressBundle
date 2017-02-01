<?php

namespace Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor;

use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\QueryHandler\DoctrineQueryHandler;
use Bilendi\DevExpressBundle\Exception\InvalidNullValueException;
use Bilendi\DevExpressBundle\Exception\UnknownComparisonException;
use Bilendi\DevExpressBundle\Exception\UnknownCompositeException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;

/**
 * Class DoctrineExpressionVisitor.
 */
class DoctrineExpressionVisitor extends AbstractExpressionVisitor
{
    /**
     * @var DoctrineQueryHandler
     */
    protected $queryHandler;

    /**
     * @var ArrayCollection
     */
    protected $usedFields;

    /**
     * @var ArrayCollection
     */
    protected $parameters;

    /**
     * @var Expr
     */
    protected $expr;

    /**
     * @var array
     */
    protected static $operators = [
        ComparisonExpression::GT => DoctrineComparison::GT,
        ComparisonExpression::GE => DoctrineComparison::GTE,
        ComparisonExpression::LT => DoctrineComparison::LT,
        ComparisonExpression::LE => DoctrineComparison::LTE,
    ];

    /**
     * DoctrineExpressionVisitor constructor.
     *
     * @param DoctrineQueryHandler $queryHandler
     */
    public function __construct(DoctrineQueryHandler $queryHandler)
    {
        $this->usedFields = new ArrayCollection();
        $this->parameters = new ArrayCollection();
        $this->queryHandler = $queryHandler;
        $this->expr = new Expr();
    }

    /**
     * @param $operator
     *
     * @return mixed|null
     */
    protected function convertOperator($operator)
    {
        return isset(self::$operators[$operator]) ? self::$operators[$operator] : null;
    }

    /**
     * @param ComparisonExpression $comparison
     *
     * @return mixed|string
     */
    protected function visitValue(ComparisonExpression $comparison)
    {
        if ($date = \DateTime::createFromFormat('Y-m-d\TH:i:se', str_replace('.000Z', '+00:00', $comparison->getValue()))) {
            return $date->setTimezone(new \DateTimezone(date_default_timezone_get()));
        }
        if ($comparison->getOperator() === ComparisonExpression::CONTAINS || $comparison->getOperator() === ComparisonExpression::NOTCONTAINS) {
            return '%'.$comparison->getValue().'%';
        } elseif ($comparison->getOperator() === ComparisonExpression::STARTSWITH) {
            return $comparison->getValue().'%';
        } elseif ($comparison->getOperator() === ComparisonExpression::ENDSWITH) {
            return '%'.$comparison->getValue();
        } else {
            return $comparison->getValue();
        }
    }

    /**
     * @param $field
     *
     * @return string
     */
    protected function visitField(string $field)
    {
        $this->usedFields->add($field);

        return $this->queryHandler->transformField($field);
    }

    /**
     * @param ComparisonExpression $comparison
     * @param string               $fieldName
     * @param mixed                $value
     *
     * @return DoctrineComparison
     */
    protected function visitComparisonWithNotNullValue(ComparisonExpression $comparison, string $fieldName, $value)
    {
        $parameterName = 'p'.$this->parameters->count();
        $operand = ':'.$parameterName;
        $this->parameters->add(new Parameter($parameterName, $value));

        switch ($comparison->getOperator()) {
            case ComparisonExpression::EQ:
                return $this->expr->eq($fieldName, $operand);
            case ComparisonExpression::NE:
                return $this->expr->neq($fieldName, $operand);
            case ComparisonExpression::CONTAINS:
            case ComparisonExpression::STARTSWITH:
            case ComparisonExpression::ENDSWITH:
                return $this->expr->like($fieldName, $operand);
            case ComparisonExpression::NOTCONTAINS:
                return $this->expr->notLike($fieldName, $operand);
            default:
                $operator = $this->convertOperator($comparison->getOperator());
                if ($operator) {
                    return new Expr\Comparison(
                        $fieldName,
                        $operator,
                        $operand
                    );
                }
                throw new UnknownComparisonException($comparison->getOperator());
        }
    }

    /**
     * @param ComparisonExpression $comparison
     * @param string               $fieldName
     *
     * @return string
     */
    protected function visitComparisonWithNullValue(ComparisonExpression $comparison, string $fieldName)
    {
        switch ($comparison->getOperator()) {
            case ComparisonExpression::EQ:
                return $this->expr->isNull($fieldName);
            case ComparisonExpression::NE:
                return $this->expr->isNotNull($fieldName);
            default:
                throw new InvalidNullValueException($comparison->getOperator());
        }
    }

    /**
     * @param ComparisonExpression $comparison
     *
     * @return DoctrineComparison
     */
    public function visitComparison(ComparisonExpression $comparison)
    {
        $value = $this->visitValue($comparison);
        $field = $comparison->getField();
        $fieldName = $this->visitField($field);

        if ($value !== null) {
            return $this->visitComparisonWithNotNullValue($comparison, $fieldName, $value);
        } else {
            return $this->visitComparisonWithNullValue($comparison, $fieldName);
        }
    }

    /**
     * @param string $type
     * @param array  $expressions
     *
     * @return Andx|Orx
     */
    public function visitProcessedCompositeExpression(string $type, array $expressions)
    {
        switch ($type) {
            case CompositeExpression::TYPE_AND:
                return new Andx($expressions);
            case CompositeExpression::TYPE_OR:
                return new Orx($expressions);
            default:
                throw new UnknownCompositeException($type);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsedFields()
    {
        return $this->usedFields;
    }
}
