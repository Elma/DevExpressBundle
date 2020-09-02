<?php

namespace Bilendi\DevExpressBundle\DataGrid\Search;

/*
 * Class SearchQueryBuilder
 * @package Bilendi\DevExpressBundle\DataGrid\Search
 */
use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\EmptyExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\Visitable;

/**
 * Class SearchQueryBuilder.
 */
class SearchQueryBuilder
{
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var SearchSort[]
     */
    protected $sortings = [];

    /**
     * @var int
     */
    protected $startIndex = 0;

    /**
     * @var null
     */
    protected $maxResults = null;

    /**
     * @return SearchQuery
     */
    public function build()
    {
        $filters = null;
        if (empty($this->filters)) {
            $filters = new EmptyExpression();
        } elseif (count($this->filters) > 1) {
            $filters = new CompositeExpression(CompositeExpression::TYPE_AND, $this->filters);
        } else {
            $filters = $this->filters[0];
        }

        return new SearchQuery($filters, $this->sortings, $this->startIndex, $this->maxResults);
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getStartIndex(): int
    {
        return $this->startIndex;
    }

    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    public function getSortings(): array
    {
        return $this->sortings;
    }

    /**
     * @return $this
     */
    public function setStartIndex(int $startIndex)
    {
        $this->startIndex = $startIndex;

        return $this;
    }

    /**
     * @param $maxResults
     *
     * @return $this
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;

        return $this;
    }

    /**
     * @return $this
     */
    public function sort(string $field, bool $desc)
    {
        $this->sortings[] = new SearchSort($field, $desc);

        return $this;
    }

    /**
     * @return $this
     */
    public function filter(Visitable $expression)
    {
        $this->filters[] = $expression;

        return $this;
    }
}
