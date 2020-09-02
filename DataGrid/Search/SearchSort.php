<?php

namespace Bilendi\DevExpressBundle\DataGrid\Search;

/**
 * Class SearchSort.
 */
class SearchSort
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var bool
     */
    private $desc;

    /**
     * SearchSort constructor.
     */
    public function __construct(string $field, bool $desc)
    {
        $this->field = $field;
        $this->desc = $desc;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return bool
     */
    public function isDesc()
    {
        return $this->desc;
    }
}
