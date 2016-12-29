<?php

namespace Bilendi\DevExpressBundle\DataGrid\Search;


class SearchGroup
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var boolean
     */
    private $desc;

    /**
     * SearchGroup constructor.
     * @param string $field
     * @param bool $desc
     */
    function __construct(string $field, bool $desc)
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
     * @return boolean
     */
    public function isDesc()
    {
        return $this->desc;
    }
}