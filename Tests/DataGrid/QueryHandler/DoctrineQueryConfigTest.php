<?php

namespace Bilendi\DevExpressBundle\DataGrid\QueryHandler;

use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use PHPUnit\Framework\TestCase;

class DoctrineQueryConfigTest extends TestCase
{
    public function testFieldMapping()
    {
        $config = new DoctrineQueryConfig();
        $config->setFieldMapping([
            'lol' => 'lol.pouet'
        ]);
        $this->assertEquals(['lol' =>'lol.pouet'], $config->getFieldMapping());
    }

    public function testDefaultFilters()
    {
        $config = new DoctrineQueryConfig();
        $config->setDefaultFilters([
            new ComparisonExpression('lol', '>', 'mdr')
        ]);
        $this->assertEquals([new ComparisonExpression('lol', '>', 'mdr')], $config->getDefaultFilters());
    }

    public function testMapFields()
    {
        $config = new DoctrineQueryConfig();
        $config->setFieldMapping([
            'lol' => 'lol.pouet'
        ]);
        $this->assertEquals('lol.pouet', $config->mapField('lol'));
        $this->assertEquals('hey', $config->mapField('hey'));
    }
}