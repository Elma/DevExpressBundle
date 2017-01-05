<?php

namespace Bilendi\DevExpressBundle\DataGrid\Search;

use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use PHPUnit\Framework\TestCase;

class SearchQueryTest extends TestCase
{
    public function testConstruct()
    {
        $search = new SearchQuery(new ComparisonExpression('haha', '>', 'hihi'), [], [], 0, null);

        $this->assertEquals(new ComparisonExpression('haha', '>', 'hihi'), $search->getFilter());
        $this->assertEquals([], $search->getSort());
        $this->assertEquals(0, $search->getStartIndex());
        $this->assertEquals(null, $search->getMaxResults());
    }

    public function testFilter()
    {
        $search = new SearchQuery(new ComparisonExpression('haha', '>', 'hihi'), [], [], 0, null);

        $search->setFilter(new ComparisonExpression('hihi', '>', 'hoho'));
        $this->assertEquals(new ComparisonExpression('hihi', '>', 'hoho'), $search->getFilter());
    }

    public function testSort()
    {
        $search = new SearchQuery(new ComparisonExpression('haha', '>', 'hihi'), [], [], 0, null);

        $search->setSort([new SearchSort('pouet', true)]);
        $this->assertEquals([new SearchSort('pouet', true)], $search->getSort());
    }

    public function testStartIndex()
    {
        $search = new SearchQuery(new ComparisonExpression('haha', '>', 'hihi'), [], [], 0, null);

        $search->setStartIndex(1);
        $this->assertEquals(1, $search->getStartIndex());
    }

    public function testMaxResults()
    {
        $search = new SearchQuery(new ComparisonExpression('haha', '>', 'hihi'), [], [], 0, null);

        $search->setMaxResults(1);
        $this->assertEquals(1, $search->getMaxResults());
    }
}
