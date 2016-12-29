<?php

namespace Bilendi\DevExpressBundle\DataGrid\Search;

use PHPUnit\Framework\TestCase;

class SearchSortTest extends TestCase
{
    public function testDesc()
    {
        $sort = new SearchSort('field', true);
        $this->assertEquals('field', $sort->getField());
        $this->assertEquals(true, $sort->isDesc());
    }

    public function testAsc()
    {
        $sort = new SearchSort('field', false);
        $this->assertEquals('field', $sort->getField());
        $this->assertEquals(false, $sort->isDesc());
    }
}