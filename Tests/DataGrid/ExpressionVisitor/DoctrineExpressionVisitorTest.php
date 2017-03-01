<?php

namespace Bilendi\DevExpressBundle\Tests\DataGrid\ExpressionVisitor;

use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\DoctrineExpressionVisitor;
use Bilendi\DevExpressBundle\DataGrid\QueryHandler\DoctrineQueryHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\Query\Parameter;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Query\Expr;

class DoctrineExpressionVisitorTest extends TestCase
{
    public function testVisitComparisonGT()
    {
        $exp = new ComparisonExpression('f1', ComparisonExpression::GT, '2017-01-29T23:00:00.000Z');
        $visitor = $this->getVisitor('f1', '2017-01-29T23:00:00.000Z');
        $actual = $visitor->visitComparison($exp);
        $expected = new Expr\Comparison('f1', DoctrineComparison::GT, ':p0');
        $this->assertEquals($expected, $actual);

        $this->assertEquals(1, count($visitor->getParameters()));

        $expected = new \DateTime('2017-01-29T23:00:00.000');
        $actual = $visitor->getParameters()[0]->getValue();
        $this->assertEquals($expected, $actual);
    }

    public function testVisitComparisonGTE()
    {
        $exp = new ComparisonExpression('f1', ComparisonExpression::GE, 'pouet');
        $actual = $this->getVisitor('f1', 'pouet')->visitComparison($exp);
        $expected = new Expr\Comparison('f1', DoctrineComparison::GTE, ':p0');
        $this->assertEquals($expected, $actual);
    }

    public function testVisitComparisonLT()
    {
        $exp = new ComparisonExpression('f1', ComparisonExpression::LT, 'pouet');
        $actual = $this->getVisitor('f1', 'pouet')->visitComparison($exp);
        $expected = new Expr\Comparison('f1', DoctrineComparison::LT, ':p0');
        $this->assertEquals($expected, $actual);
    }

    public function testVisitComparisonLTE()
    {
        $exp = new ComparisonExpression('f1', ComparisonExpression::LE, 'pouet');
        $actual = $this->getVisitor('f1', 'pouet')->visitComparison($exp);
        $expected = new Expr\Comparison('f1', DoctrineComparison::LTE, ':p0');
        $this->assertEquals($expected, $actual);
    }

    public function testVisitComparisonEQ()
    {
        $exp = new ComparisonExpression('f1', ComparisonExpression::EQ, 'pouet');
        $actual = $this->getVisitor('f1', 'pouet')->visitComparison($exp);
        $expected = new Expr\Comparison('f1', DoctrineComparison::EQ, ':p0');
        $this->assertEquals($expected, $actual);
    }

    public function testVisitComparisonNE()
    {
        $exp = new ComparisonExpression('f1', ComparisonExpression::NE, 'pouet');
        $actual = $this->getVisitor('f1', 'pouet')->visitComparison($exp);
        $expected = new Expr\Comparison('f1', DoctrineComparison::NEQ, ':p0');
        $this->assertEquals($expected, $actual);
    }

    public function testVisitComparisonContains()
    {
        $visitor = $this->getVisitor('f1', 'pouet');
        $exp = new ComparisonExpression('f1', ComparisonExpression::CONTAINS, 'pouet');
        $actual = $visitor->visitComparison($exp);
        $expected = new Expr\Comparison('f1', 'LIKE', ':p0');
        $this->assertEquals($expected, $actual);
        $this->assertEquals(new ArrayCollection([
            new Parameter('p0', '%pouet%'),
        ]), $visitor->getParameters());
    }

    public function testVisitComparisonNotContains()
    {
        $visitor = $this->getVisitor('f1', 'pouet');
        $exp = new ComparisonExpression('f1', ComparisonExpression::NOTCONTAINS, 'pouet');
        $actual = $visitor->visitComparison($exp);
        $expected = new Expr\Comparison('f1', 'NOT LIKE', ':p0');
        $this->assertEquals($expected, $actual);
        $this->assertEquals(new ArrayCollection([
            new Parameter('p0', '%pouet%'),
        ]), $visitor->getParameters());
    }

    public function testVisitComparisonStartsWith()
    {
        $visitor = $this->getVisitor('f1', 'pouet');
        $exp = new ComparisonExpression('f1', ComparisonExpression::STARTSWITH, 'pouet');
        $actual = $visitor->visitComparison($exp);
        $expected = new Expr\Comparison('f1', 'LIKE', ':p0');
        $this->assertEquals($expected, $actual);
        $this->assertEquals(new ArrayCollection([
            new Parameter('p0', 'pouet%'),
        ]), $visitor->getParameters());
    }

    public function testVisitComparisonEndsWith()
    {
        $visitor = $this->getVisitor('f1', 'pouet');
        $exp = new ComparisonExpression('f1', ComparisonExpression::ENDSWITH, 'pouet');
        $actual = $visitor->visitComparison($exp);
        $expected = new Expr\Comparison('f1', 'LIKE', ':p0');
        $this->assertEquals($expected, $actual);
        $this->assertEquals(new ArrayCollection([
            new Parameter('p0', '%pouet'),
        ]), $visitor->getParameters());
    }

    public function testVisitComparisonNull()
    {
        $exp = new ComparisonExpression('f1', ComparisonExpression::EQ, null);
        $actual = $this->getVisitor('f1', null)->visitComparison($exp);
        $expected = 'f1 IS NULL';
        $this->assertEquals($expected, $actual);
    }

    public function testVisitComparisonNotNull()
    {
        $exp = new ComparisonExpression('f1', ComparisonExpression::NE, null);
        $actual = $this->getVisitor('f1', null)->visitComparison($exp);
        $expected = 'f1 IS NOT NULL';
        $this->assertEquals($expected, $actual);
    }

    public function testVisitProcessedCompositeExpressionAnd()
    {
        $actual = $this->getVisitor('f1', null)->visitProcessedCompositeExpression(CompositeExpression::TYPE_AND, []);
        $expected = new Expr\Andx([]);
        $this->assertEquals($expected, $actual);
    }

    public function testVisitProcessedCompositeExpressionOr()
    {
        $actual = $this->getVisitor('f1', null)->visitProcessedCompositeExpression(CompositeExpression::TYPE_OR, []);
        $expected = new Expr\Orx([]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException     \Bilendi\DevExpressBundle\Exception\UnknownCompositeException
     */
    public function testVisitProcessedCompositeExpressionException()
    {
        $this->getVisitor('f1', null)->visitProcessedCompositeExpression('haha', []);
    }

    protected function getVisitor($visitedField, $visitedValue)
    {
        $handler = $this->getHandlerMock();
        $handler->method('transformField')->willReturn('f1');
        $handler->method('transformFieldCase')->willReturn($visitedField);
        $handler->method('transformValueCase')->willReturn($visitedValue);
        $visitor = new DoctrineExpressionVisitor($handler);

        return $visitor;
    }

    protected function getHandlerMock()
    {
        $mock = $this->getMockBuilder(DoctrineQueryHandler::class)
             ->disableOriginalConstructor()
             ->getMock();

        return $mock;
    }
}
