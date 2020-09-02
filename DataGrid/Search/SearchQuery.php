<?php

namespace Bilendi\DevExpressBundle\DataGrid\Search;

use Bilendi\DevExpressBundle\DataGrid\Expression\Visitable;

/**
 * Class SearchQuery.
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
     *
     * @param $filter
     * @param $sort
     * @param $startIndex
     * @param $maxResults
     */
    public function __construct($filter, $sort, $startIndex, $maxResults)
    {
        $this->filter = $filter;
        $this->sort = $sort;
        if (null !== $this->startIndex) {
            $this->startIndex = (int) $startIndex;
        }
        if (null !== $maxResults) {
            $this->maxResults = (int) $maxResults;
        }
    }

    public function getFilter(): Visitable
    {
        return $this->filter;
    }

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

    public function setSort(array $sort)
    {
        $this->sort = $sort;
    }

    public function getStartIndex(): int
    {
        return $this->startIndex;
    }

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
