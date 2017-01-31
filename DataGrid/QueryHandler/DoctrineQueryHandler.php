<?php

namespace Bilendi\DevExpressBundle\DataGrid\QueryHandler;

use Bilendi\DevExpressBundle\DataGrid\Expression\Visitable;
use Bilendi\DevExpressBundle\DataGrid\ExpressionVisitor\DoctrineExpressionVisitor;
use Bilendi\DevExpressBundle\DataGrid\Search\SearchQuery;
use Bilendi\DevExpressBundle\DataGrid\Search\SearchSort;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DoctrineQueryHandler.
 */
class DoctrineQueryHandler
{
    /**
     * @var DoctrineQueryConfig
     */
    protected $queryConfig;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var SearchQuery
     */
    protected $searchQuery;

    /**
     * DoctrineQueryHandler constructor.
     *
     * @param DoctrineQueryConfig $queryConfig
     * @param QueryBuilder        $queryBuilder
     * @param SearchQuery         $searchQuery
     */
    public function __construct(DoctrineQueryConfig $queryConfig, QueryBuilder $queryBuilder, SearchQuery $searchQuery)
    {
        $this->queryConfig = $queryConfig;
        $this->queryBuilder = $queryBuilder;
        $this->searchQuery = $searchQuery;
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function transformField(string $field)
    {
        return $this->queryConfig->mapField($field);
    }

    /**
     * @param bool $noDefaultFilter
     *
     * @return QueryBuilder
     */
    public function addAllModifiers($noDefaultFilter = false)
    {
        $this->addFilters($noDefaultFilter);
        $this->addSorting();
        $this->addPagination();

        return $this->queryBuilder;
    }

    /**
     * @param bool $noDefaultFilter
     *
     * @return DoctrineQueryHandler
     */
    public function addFilters($noDefaultFilter = false): DoctrineQueryHandler
    {
        $visitor = new DoctrineExpressionVisitor($this);
        $filters = $this->searchQuery->getFilter()->visit($visitor);

        if ($filters !== null) {
            $this->queryBuilder->andWhere($filters);
        }

        if (!$noDefaultFilter) {
            \Functional\map($this->queryConfig->getDefaultFilters(), function (Visitable $comparison) use ($visitor) {
                $this->queryBuilder->andWhere($comparison->visit($visitor));
            });
        }

        \Functional\map($visitor->getParameters(), function (Parameter $parameter) {
            $this->queryBuilder->setParameter($parameter->getName(), $parameter->getValue());
        });

        return $this;
    }

    /**
     * @return DoctrineQueryHandler
     */
    public function addSorting(): DoctrineQueryHandler
    {
        \Functional\each($this->searchQuery->getSort(), function (SearchSort $sort) {
            $this->queryBuilder->addOrderBy($this->transformField($sort->getField()),
                $sort->isDesc() ? 'desc' : 'asc');
        });

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function addPagination(): QueryBuilder
    {
        $this->queryBuilder->setFirstResult($this->searchQuery->getStartIndex());
        if (($maxResults = $this->searchQuery->getMaxResults()) !== null) {
            $this->queryBuilder->setMaxResults($maxResults);
        }

        return $this->queryBuilder;
    }
}
