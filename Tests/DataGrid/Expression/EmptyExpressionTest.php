<?php

namespace Bilendi\DevExpressBundle\Tests\DataGrid\Expression;

use Bilendi\DevExpressBundle\DataGrid\Expression\EmptyExpression;
use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;
use PHPUnit\Framework\TestCase;

class EmptyExpressionTest extends TestCase
{
    public function testVisit()
    {
        $comp = new EmptyExpression();

        $mock = $this->getMockBuilder(AbstractExpressionVisitor::class)
            ->setMethods(['visitEmpty'])
            ->getMockForAbstractClass();

        $mock->expects($this->once())
            ->method('visitEmpty');

        $comp->visit($mock);
    }
}