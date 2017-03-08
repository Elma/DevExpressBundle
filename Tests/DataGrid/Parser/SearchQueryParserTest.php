<?php

namespace Bilendi\DevExpressBundle\Tests\DataGrid\Parser;

use Bilendi\DevExpressBundle\DataGrid\Expression\ComparisonExpression;
use Bilendi\DevExpressBundle\DataGrid\Expression\CompositeExpression;
use Bilendi\DevExpressBundle\DataGrid\Parser\SearchQueryParser;
use Bilendi\DevExpressBundle\DataGrid\Search\SearchSort;
use PHPUnit\Framework\TestCase;

class SearchQueryParserTest extends TestCase
{
    public function testParseStartIndex()
    {
        $parser = new SearchQueryParser();
        $this->assertEquals(0, $parser->getBuilder()->getStartIndex());

        $dummy = new \stdClass();
        $dummy->skip = 3;

        $parser->parseStartIndex($dummy);
        $this->assertEquals(3, $parser->getBuilder()->getStartIndex());
    }

    /**
     * @expectedException     \Bilendi\DevExpressBundle\Exception\NotNumericException
     */
    public function testParseStartIndexException()
    {
        $parser = new SearchQueryParser();
        $this->assertEquals(0, $parser->getBuilder()->getStartIndex());

        $dummy = new \stdClass();
        $dummy->skip = 'pouet';
        $parser->parseStartIndex($dummy);
    }

    public function testParseMaxResults()
    {
        $parser = new SearchQueryParser();
        $this->assertEquals(0, $parser->getBuilder()->getMaxResults());

        $dummy = new \stdClass();
        $dummy->take = 3;

        $parser->parseMaxResults($dummy);
        $this->assertEquals(3, $parser->getBuilder()->getMaxResults());
    }

    /**
     * @expectedException     \Bilendi\DevExpressBundle\Exception\NotNumericException
     */
    public function testParseMaxResultsException()
    {
        $parser = new SearchQueryParser();
        $this->assertEquals(0, $parser->getBuilder()->getMaxResults());

        $dummy = new \stdClass();
        $dummy->take = 'pouet';

        $parser->parseMaxResults($dummy);
    }

    public function testParseSorting()
    {
        $parser = new SearchQueryParser();
        $this->assertEquals(0, $parser->getBuilder()->getMaxResults());

        $dummy = new \stdClass();

        $sort1 = new \stdClass();
        $sort1->desc = false;
        $sort1->selector = 'pouet';

        $sort2 = new \stdClass();
        $sort2->desc = true;
        $sort2->selector = 'lol';

        $dummy->sort = [$sort1, $sort2];

        $parser->parseSort($dummy);

        $expected = [
            new SearchSort('pouet', false),
            new SearchSort('lol', true),
        ];

        $this->assertEquals($expected, $parser->getBuilder()->getSortings());
    }

    /**
     * @expectedException     \Bilendi\DevExpressBundle\Exception\NotArrayException
     */
    public function testParseSortingExpcetion()
    {
        $parser = new SearchQueryParser();
        $this->assertEquals(0, $parser->getBuilder()->getMaxResults());

        $dummy = new \stdClass();
        $dummy->sort = 'pouet';

        $parser->parseSort($dummy);
    }

    public function testParseDisjunctionSimple()
    {
        $parser = new SearchQueryParser();
        $filter = [
            'number',
            '<>',
            0,
        ];
        $actual = $parser->parseDisjunction($filter);
        $expected = new ComparisonExpression('number', '<>', 0);
        $this->assertEquals($expected, $actual);
    }

    public function testParseDisjunctionOneOr()
    {
        $parser = new SearchQueryParser();
        $filter = [
            ['number', '<>', 0],
            'or',
            ['pouet', '>', 'lol'],
        ];
        $actual = $parser->parseDisjunction($filter);
        $exps = [
            new ComparisonExpression('number', '<>', 0),
            new ComparisonExpression('pouet', '>', 'lol'),
        ];
        $expected = new CompositeExpression(CompositeExpression::TYPE_OR, $exps);
        $this->assertEquals($expected, $actual);
    }

    public function testParseDisjunctionMultipleOr()
    {
        $parser = new SearchQueryParser();
        $filter = [
            ['number', '<>', 0],
            'or',
            ['pouet', '>', 'lol'],
            'or',
            ['yo', '=', 'salut'],
            'or',
            ['ui', '=', 'ui'],
        ];
        $actual = $parser->parseDisjunction($filter);
        $exps = [
            new ComparisonExpression('number', '<>', 0),
            new ComparisonExpression('pouet', '>', 'lol'),
            new ComparisonExpression('yo', '=', 'salut'),
            new ComparisonExpression('ui', '=', 'ui'),
        ];
        $expected = new CompositeExpression(CompositeExpression::TYPE_OR, $exps);
        $this->assertEquals($expected, $actual);
    }

    public function testParseDisjunctionOneAnd()
    {
        $parser = new SearchQueryParser();
        $filter = [
            ['number', '<>', 0],
            'and',
            ['pouet', '>', 'lol'],
        ];
        $actual = $parser->parseDisjunction($filter);
        $exps = [
            new ComparisonExpression('number', '<>', 0),
            new ComparisonExpression('pouet', '>', 'lol'),
        ];
        $expected = new CompositeExpression(CompositeExpression::TYPE_AND, $exps);
        $this->assertEquals($expected, $actual);
    }

    public function testParseDisjunctionMultipleAnd()
    {
        $parser = new SearchQueryParser();
        $filter = [
            ['number', '<>', 0],
            'and',
            ['pouet', '>', 'lol'],
            'and',
            ['yo', '=', 'salut'],
        ];
        $actual = $parser->parseDisjunction($filter);
        $exps = [
            new ComparisonExpression('number', '<>', 0),
            new ComparisonExpression('pouet', '>', 'lol'),
            new ComparisonExpression('yo', '=', 'salut'),
        ];
        $expected = new CompositeExpression(CompositeExpression::TYPE_AND, $exps);
        $this->assertEquals($expected, $actual);
    }

    public function testParseDisjunctionAndInOr()
    {
        $parser = new SearchQueryParser();
        $filter = [
            ['number', '<>', 0],
            'and',
            [
                ['pouet', '>', 'lol'],
                'or',
                ['yo', '=', 'salut'],
            ],
        ];
        $actual = $parser->parseDisjunction($filter);
        $expOr = [
            new ComparisonExpression('pouet', '>', 'lol'),
            new ComparisonExpression('yo', '=', 'salut'),
        ];
        $compositeOr = new CompositeExpression(CompositeExpression::TYPE_OR, $expOr);
        $expected = new CompositeExpression(
            CompositeExpression::TYPE_AND, [
                new ComparisonExpression('number', '<>', 0),
                $compositeOr,
            ]
        );
        $this->assertEquals($expected, $actual);
    }

    public function testParseDisjunctionOrInAnd()
    {
        $parser = new SearchQueryParser();
        $filter = [
            ['number', '<>', 0],
            'or',
            [
                ['pouet', '>', 'lol'],
                'and',
                ['yo', '=', 'salut'],
            ],
        ];
        $actual = $parser->parseDisjunction($filter);
        $expAnd = [
            new ComparisonExpression('pouet', '>', 'lol'),
            new ComparisonExpression('yo', '=', 'salut'),
        ];
        $compositeAnd = new CompositeExpression(CompositeExpression::TYPE_AND, $expAnd);
        $expected = new CompositeExpression(
            CompositeExpression::TYPE_OR, [
                new ComparisonExpression('number', '<>', 0),
                $compositeAnd,
            ]
        );
        $this->assertEquals($expected, $actual);
    }

    public function testDisjunctionDepth3()
    {
        $parser = new SearchQueryParser();
        $filter = [
            ['number', '<>', 0],
            'or',
            [
                ['pouet', '>', 'lol'],
                'and',
                [
                    ['yo', '=', 'salut'],
                    'or',
                    ['tug', '<>', 'thug'],
                ],
            ],
        ];
        $actual = $parser->parseDisjunction($filter);

        $expNestedOr = [
            new ComparisonExpression('yo', '=', 'salut'),
            new ComparisonExpression('tug', '<>', 'thug'),
        ];
        $nestedOr = new CompositeExpression(CompositeExpression::TYPE_OR, $expNestedOr);

        $compositeAnd = new CompositeExpression(
            CompositeExpression::TYPE_AND, [
                new ComparisonExpression('pouet', '>', 'lol'),
                $nestedOr,
            ]
        );

        $expected = new CompositeExpression(
            CompositeExpression::TYPE_OR, [
                new ComparisonExpression('number', '<>', 0),
                $compositeAnd,
            ]
        );
        $this->assertEquals($expected, $actual);
    }

    public function testDisjunctionSPM()
    {
        $parser = new SearchQueryParser();
        $filter = [
            [
                ["projectManager", "contains", "test"],
                "and",
                ["seller", "contains", "test"]
            ],
            "and",
            [
                ["status", "=", "RG"],
                "or",
                ["status", "=", "HD"]
            ],
        ];
        $actual = $parser->parseDisjunction($filter);

        $expNestedOr = [
            new ComparisonExpression('status', '=', 'RG'),
            new ComparisonExpression('status', '=', 'HD'),
        ];
        $nestedOr = new CompositeExpression(CompositeExpression::TYPE_OR, $expNestedOr);

        $expNestedAnd = [
            new ComparisonExpression('projectManager', 'contains', 'test'),
            new ComparisonExpression('seller', 'contains', 'test'),
        ];
        $nestedAnd = new CompositeExpression(CompositeExpression::TYPE_AND, $expNestedAnd);

        $expected = new CompositeExpression(
            CompositeExpression::TYPE_AND, [
                $nestedAnd,
                $nestedOr,
            ]
        );
        $this->assertEquals($expected, $actual);
    }
}
