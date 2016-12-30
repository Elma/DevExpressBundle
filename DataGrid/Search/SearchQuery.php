<?php

namespace Bilendi\DevExpressBundle\DataGrid\Search;
use Bilendi\DevExpressBundle\DataGrid\Expression\Visitable;


/**
 * Class SearchQuery
 * @package Bilendi\DevExpressBundle\DataGrid\Search
 */
class SearchQuery
{
    /**
     * @var Visitable
     */
    protected $filter = [];

    /**
     * @var SearchSort[]
     */
    protected $sort = [];

    /**
     * @var int
     */
    protected $startIndex = 0;

    /**
     * @var int|null
     */
    protected $maxResults = null;

    /**
     * SearchQuery constructor.
     * @param $filter
     * @param $sort
     * @param $startIndex
     * @param $maxResults
     */
    function __construct($filter, $sort, $startIndex, $maxResults) {
        $this->filter = $filter;
        $this->sort = $sort;
        if ($this->startIndex !== null) {
            $this->startIndex = (int) $startIndex;
        }
        if ($maxResults !== null) {
            $this->maxResults = (int) $maxResults;
        }
    }

    /**
     * @return Visitable
     */
    public function getFilter(): Visitable
    {
        return $this->filter;
    }

    /**
     * @param Visitable $filter
     */
    public function setFilter(Visitable $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return SearchSort[]
     */
    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * @param array $sort
     */
    public function setSort(array $sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return int
     */
    public function getStartIndex(): int
    {
        return $this->startIndex;
    }

    /**
     * @param int $startIndex
     */
    public function setStartIndex(int $startIndex)
    {
        $this->startIndex = $startIndex;
    }

    /**
     * @return int|null
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @param int|null $maxResults
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
    }
}