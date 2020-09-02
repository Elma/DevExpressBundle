<?php

namespace Bilendi\DevExpressBundle\DataGrid\QueryHandler;

use Bilendi\DevExpressBundle\DataGrid\Expression\Visitable;

/**
 * Class DoctrineQueryConfig.
 */
class DoctrineQueryConfig
{
    /**
     * @var array
     */
    protected $fieldMapping = [];

    /**
     * @var Visitable[]
     */
    protected $defaultFilters = [];

    /**
     * @var bool
     */
    protected $caseSensitive = false;

    /**
     * DoctrineQueryConfig constructor.
     *
     * @param bool $caseSensitive
     */
    public function __construct(array $fieldMapping = [], array $defaultFilters = [], $caseSensitive = false)
    {
        $this->fieldMapping = $fieldMapping;
        $this->defaultFilters = $defaultFilters;
        $this->caseSensitive = $caseSensitive;
    }

    /**
     * @return Visitable[]
     */
    public function getDefaultFilters(): array
    {
        return $this->defaultFilters;
    }

    public function setDefaultFilters(array $defaultFilters)
    {
        $this->defaultFilters = $defaultFilters;
    }

    public function getFieldMapping(): array
    {
        return $this->fieldMapping;
    }

    public function setFieldMapping(array $fieldMapping)
    {
        $this->fieldMapping = $fieldMapping;
    }

    /**
     * @return string
     */
    public function mapField(string $field)
    {
        if (array_key_exists($field, $this->fieldMapping)) {
            return $this->fieldMapping[$field];
        }

        return $field;
    }

    public function isCaseSensitive(): bool
    {
        return $this->caseSensitive;
    }

    public function setCaseSensitive(bool $caseSensitive)
    {
        $this->caseSensitive = $caseSensitive;
    }
}
