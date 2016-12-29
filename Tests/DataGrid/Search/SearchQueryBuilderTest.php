<?php

namespace Bilendi\DevExpressBundle\DataGrid\Search;


use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\EmptyExpression;
use PHPUnit\Framework\TestCase;

class SearchQueryBuilderTest extends TestCase
{
    public function testFilter()
    {
        $builder = new SearchQueryBuilder();
        $builder->filter(new ComparisonExpression('hihi', '>', 'hoho'));
        $this->assertEquals([new ComparisonExpression('hihi', '>', 'hoho')], $builder->getFilters());
    }

    public function testSort()
    {
        $builder = new SearchQueryBuilder();
        $builder->sort('pouet', true);
        $this->assertEquals([new SearchSort('pouet', true)], $builder->getSortings());
    }

    public function testGroup()
    {
        $builder = new SearchQueryBuilder();
        $builder->group('pouet', true);
        $this->assertEquals([new SearchGroup('pouet', true)], $builder->getGroupings());
    }

    public function testStartIndex()
    {
        $builder = new SearchQueryBuilder();
        $builder->setStartIndex(1);
        $this->assertEquals(1, $builder->getStartIndex());
    }

    public function testMaxResults()
    {
        $builder = new SearchQueryBuilder();
        $builder->setMaxResults(1);
        $this->assertEquals(1, $builder->getMaxResults());
    }

    public function testBuildNoFilter()
    {
        $builder = new SearchQueryBuilder();
        $query = $builder->build();

        $this->assertInstanceOf(SearchQuery::class, $query);
        $this->assertEquals(new EmptyExpression(), $query->getFilter());
    }

    public function testBuildOneFilter()
    {
        $builder = new SearchQueryBuilder();
        $builder->filter(new ComparisonExpression('hihi', '>', 'hoho'));
        $query = $builder->build();

        $this->assertInstanceOf(SearchQuery::class, $query);
        $this->assertEquals(new ComparisonExpression('hihi', '>', 'hoho'), $query->getFilter());
    }

    public function testBuildMultipleFilter()
    {
        $builder = new SearchQueryBuilder();
        $builder->filter(new ComparisonExpression('hihi', '>', 'hoho'));
        $builder->filter(new ComparisonExpression('huhu', '>', 'hihi'));
        $query = $builder->build();

        $this->assertInstanceOf(SearchQuery::class, $query);
        $expected = new CompositeExpression(CompositeExpression::TYPE_AND, [
            new ComparisonExpression('hihi', '>', 'hoho'),
            new ComparisonExpression('huhu', '>', 'hihi')
        ]);
        $this->assertEquals($expected, $query->getFilter());
    }
}