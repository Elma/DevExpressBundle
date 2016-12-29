<?php

namespace Bilendi\DevExpressBundle\Tests\DataGrid\Expression;

use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;
use PHPUnit\Framework\TestCase;

class CompositeExpressionTest extends TestCase
{
    public function testConstruct()
    {
        $expressions = [];
        $comp = new CompositeExpression('and', $expressions);
        $this->assertEquals('and', $comp->getType());
        $this->assertEquals([], $comp->getExpressions());
    }

    public function testVisit()
    {
        $comp = new CompositeExpression('and', []);

        $mock = $this->getMockBuilder(AbstractExpressionVisitor::class)
            ->setMethods(['visitCompositeExpression'])
            ->getMockForAbstractClass();

        $mock->expects($this->once())
            ->method('visitCompositeExpression');

        $comp->visit($mock);
    }
}