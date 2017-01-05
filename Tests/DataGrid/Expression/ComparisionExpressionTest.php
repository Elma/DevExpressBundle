<?php

namespace Bilendi\DevExpressBundle\Tests\DataGrid\Expression;

use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;
use PHPUnit\Framework\TestCase;

class ComparisionExpressionTest extends TestCase
{
    public function testConstruct()
    {
        $comp = new ComparisonExpression('field', '=', 2);
        $this->assertEquals('field', $comp->getField());
        $this->assertEquals('=', $comp->getOperator());
        $this->assertEquals(2, $comp->getValue());
    }

    public function testVisit()
    {
        $comp = new ComparisonExpression('field', '=', 2);

        $mock = $this->getMockBuilder(AbstractExpressionVisitor::class)
                     ->getMockForAbstractClass();
        $mock->expects($this->once())
            ->method('visitComparison');
        $comp->visit($mock);
    }
}
