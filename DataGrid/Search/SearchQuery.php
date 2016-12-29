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
     * @var SearchGroup[]
     */
    protected $group;

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
     * @param $group
     * @param $startIndex
     * @param $maxResults
     */
    function __construct($filter, $sort, $group, $startIndex, $maxResults) {
        $this->filter = $filter;
        $this->sort = $sort;
        $this->group = $group;
        if ($this->startIndex !== null) {
            $this->startIndex = (int) $startIndex;
        }
        if ($maxResults !== null) {
            $this->maxResults = (int) $maxResults;
        }
    }

    /**
     * @return SearchGroup[]
     */
    public function getGroup(): array
    {
        return $this->group;
    }

    /**
     * @param SearchGroup[] $group
     */
    public function setGroup(array $group)
    {
        $this->group = $group;
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