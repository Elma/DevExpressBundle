<?php

namespace Bilendi\DevExpressBundle\DataGrid\Parser;

use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\Visitable;
use Bilendi\DevExpressBundle\DataGrid\Search\SearchQuery;
use Bilendi\DevExpressBundle\DataGrid\Search\SearchQueryBuilder;
use Bilendi\DevExpressBundle\Exception\NotArrayException;
use Bilendi\DevExpressBundle\Exception\NotNumericException;

/**
 * Class SearchQueryParser.
 */
class SearchQueryParser
{
    /**
     * @var SearchQueryBuilder
     */
    protected $builder;

    /**
     * SearchQueryParser constructor.
     */
    public function __construct()
    {
        $this->builder = new SearchQueryBuilder();
    }

    public function getBuilder(): SearchQueryBuilder
    {
        return $this->builder;
    }

    public function parse(\stdClass $data): SearchQuery
    {
        if (isset($data->take)) {
            $this->parseMaxResults($data);
        }
        if (isset($data->skip)) {
            $this->parseStartIndex($data);
        }
        if (isset($data->sort)) {
            $this->parseSort($data);
        }
        if (isset($data->filter)) {
            $this->parseFilter($data);
        }

        return $this->builder->build();
    }

    /**
     * @throws NotNumericException
     */
    public function parseStartIndex(\stdClass $data)
    {
        if (is_numeric($data->skip)) {
            $this->builder->setStartIndex(intval($data->skip));
        } else {
            throw new NotNumericException('skip');
        }
    }

    /**
     * @throws NotNumericException
     */
    public function parseMaxResults(\stdClass $data)
    {
        if (is_numeric($data->take)) {
            $this->builder->setMaxResults(intval($data->take));
        } else {
            throw new NotNumericException('take');
        }
    }

    /**
     * @throws NotArrayException
     */
    public function parseSort(\stdClass $data)
    {
        if (is_array($data->sort)) {
            \Functional\map(
                $data->sort,
                function (\stdClass $sort) {
                    $this->builder->sort($sort->selector, $sort->desc);
                }
            );
        } else {
            throw new NotArrayException('sort');
        }
    }

    public function parseComparison(array $filter): ComparisonExpression
    {
        return new ComparisonExpression($filter[0], $filter[1], $filter[2]);
    }

    /**
     * @throws \Exception
     */
    public function parseDisjunction(array $filter, CompositeExpression $parent = null): Visitable
    {
        if (1 == count($filter)) {
            if (null === $parent) {
                return $this->parseComparison(array_shift($filter));
            } else {
                $parent->addExpression($this->parseDisjunction(array_shift($filter)));

                return $parent;
            }
        } elseif (count($filter) >= 3) {
            $first = array_shift($filter);
            $second = array_shift($filter);
            if ('and' === $second || 'or' === $second) {
                if ('and' === $second) {
                    $newComposite = new CompositeExpression(CompositeExpression::TYPE_AND, []);
                } else {
                    $newComposite = new CompositeExpression(CompositeExpression::TYPE_OR, []);
                }
                if (null !== $parent) {
                    if (count($filter[0]) > 1 && 0 === count($parent->getExpressions())) {
                        $newComposite->addExpression($this->parseDisjunction($first, $newComposite));

                        return $this->parseDisjunction($filter, $newComposite);
                    } else {
                        $parent->addExpression($this->parseDisjunction($first, $newComposite));

                        return $this->parseDisjunction($filter, $parent);
                    }
                } else {
                    $newComposite->addExpression($this->parseDisjunction($first, $newComposite));

                    return $this->parseDisjunction($filter, $newComposite);
                }
            } else {
                return $this->parseComparison([$first, $second, $filter[0]]);
            }
        } elseif (2 == count($filter)) {
            $first = array_shift($filter);
            $second = array_shift($filter);

            if ('!' === $first) {
                $newComposite = new CompositeExpression(CompositeExpression::TYPE_NOT, []);

                $newComposite->addExpression($this->parseDisjunction($second, $newComposite));

                return $newComposite;
            }
        }

        throw new \Exception('Error for format filter:'.print_r($filter, true));
    }

    /**
     * @throws NotArrayException
     */
    public function parseFilter(\stdClass $data)
    {
        if (is_array($data->filter)) {
            $this->builder->filter($this->parseDisjunction($data->filter));
        } else {
            throw new NotArrayException('filter');
        }
    }
}
