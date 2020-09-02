<?php

namespace Bilendi\DevExpressBundle\DataGrid\Search;

class SearchGroup
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
     * SearchGroup constructor.
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
