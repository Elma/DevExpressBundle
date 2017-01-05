<?php

namespace Bilendi\DevExpressBundle\Tests\DataGrid\ExpressionVisitor;

use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\AbstractExpressionVisitor;
use PHPUnit\Framework\TestCase;

class AbstractExpressionVisitorTest extends TestCase
{
    public function testVisitEmpty()
    {
        $visitor = new ExpressionVisitorStub();
        $this->assertNull($visitor->visitEmpty());
    }

    public function testVisitComparison()
    {
        $visitor = new ExpressionVisitorStub();
        $this->assertTrue($visitor->visitComparison($this->getComparisonExpressionMock()));
    }

    public function testvisitCompositeExpression()
    {
        $visitor = new ExpressionVisitorStub();
        $this->assertTrue($visitor->visitCompositeExpression($this->getCompositeExpressionMock()));
    }

    protected function getCompositeExpressionMock()
    {
        $mock = $this->getMockBuilder(CompositeExpression::class)
             ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getExpressions')
            ->willReturn([]);
        $mock->method('getType')
            ->willReturn(CompositeExpression::TYPE_OR);

        return $mock;
    }

    protected function getComparisonExpressionMock()
    {
        $mock = $this->getMockBuilder(ComparisonExpression::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}

class ExpressionVisitorStub extends AbstractExpressionVisitor
{
    public function visitComparison(ComparisonExpression $comparison)
    {
        return true;
    }

    public function visitProcessedCompositeExpression(string $type, array $expressions)
    {
        return true;
    }
}
