<?php

namespace Bilendi\DevExpressBundle\DataGrid\QueryHandler;


use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\Search\SearchGroup;
use Bilendi\DevExpressBundle\DataGrid\Search\SearchQuery;
use Bilendi\DevExpressBundle\DataGrid\Search\SearchSort;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class DoctrineQueryHandlerTest extends TestCase
{
    public function testTransformField()
    {
        $config = $this->getConfigMock();
        $config->expects($this->once())
                ->method('mapField');
        $handler = new DoctrineQueryHandler($config, $this->getQBMock(), $this->getQueryMock());
        $handler->transformField('pouet');
    }

    public function testAddFilters()
    {
        $config = $this->getConfigMock();
        $config->expects($this->once())
            ->method('mapField')
            ->willReturn('lol');

        $query = $this->getQueryMock();
        $query->expects($this->once())
            ->method('getFilter')
            ->willReturn(new ComparisonExpression('lol', '>', 'haha'));

        $qb = $this->getQBMock();
        $qb->expects($this->once())
            ->method('andWhere')
            ->with(new Expr\Comparison('lol', DoctrineComparison::GT, ':p0'));

        $qb->expects($this->once())
            ->method('setParameter')
            ->with('p0', 'haha');
        $handler = new DoctrineQueryHandler($config, $qb, $query);
        $handler->addFilters();
    }

    public function testAddSorting()
    {
        $config = $this->getConfigMock();
        $config->expects($this->at(0))
                ->method('mapField')
                ->with('lol')
                ->willReturn('lol');
        $config->expects($this->at(1))
            ->method('mapField')
            ->with('haha')
            ->willReturn('haha');

        $query = $this->getQueryMock();
        $query->expects($this->once())
                ->method('getSort')
                ->willReturn([
                    new SearchSort('lol', true),
                    new SearchSort('haha', false)
                ]);

        $qb = $this->getQBMock();
        $qb->expects($this->at(0))
            ->method('addOrderBy')
            ->with('lol', 'desc');
        $qb->expects($this->at(1))
            ->method('addOrderBy')
            ->with('haha', 'asc');
        $handler = new DoctrineQueryHandler($config, $qb, $query);
        $handler->addSorting();
    }

    public function testAddGrouping()
    {
        $config = $this->getConfigMock();
        $config->expects($this->at(0))
            ->method('mapField')
            ->with('lol')
            ->willReturn('lol');
        $config->expects($this->at(1))
            ->method('mapField')
            ->with('haha')
            ->willReturn('haha');

        $query = $this->getQueryMock();
        $query->expects($this->once())
            ->method('getGroup')
            ->willReturn([
                new SearchGroup('lol', true),
                new SearchGroup('haha', false)
            ]);

        $qb = $this->getQBMock();
        $qb->expects($this->at(0))
            ->method('groupBy')
            ->with('lol');
        $qb->expects($this->at(1))
            ->method('addOrderBy')
            ->with('lol', 'desc');
        $qb->expects($this->at(2))
            ->method('groupBy')
            ->with('haha');
        $qb->expects($this->at(3))
            ->method('addOrderBy')
            ->with('haha', 'asc');
        $handler = new DoctrineQueryHandler($config, $qb, $query);
        $handler->addGrouping();
    }

    public function testAddPagination()
    {
        $qb = $this->getQBMock();
        $qb->method('setFirstResult')
            ->with(1);
        $qb->method('setMaxResults')
            ->with(2);

        $query = $this->getQueryMock();
        $query->expects($this->once())
            ->method('getStartIndex')
            ->willReturn(1);
        $query->expects($this->once())
            ->method('getMaxResults')
            ->willReturn(2);
        $handler = new DoctrineQueryHandler($this->getConfigMock(), $qb, $query);
        $handler->addPagination();
    }

    protected function getConfigMock()
    {
        return $this->getMockBuilder(DoctrineQueryConfig::class)
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    protected function getQBMock()
    {
        return $this->getMockBuilder(QueryBuilder::class)
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    protected function getQueryMock()
    {
        return $this->getMockBuilder(SearchQuery::class)
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}