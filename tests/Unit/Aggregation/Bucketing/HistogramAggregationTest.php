<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\Aggregation\Bucketing;

use ONGR\ElasticsearchDSL\Aggregation\AbstractAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\HistogramAggregation;

/**
 * Unit test for children aggregation.
 */
class HistogramAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests if ChildrenAggregation#getArray throws exception when expected.
     */
    public function testGetArrayException()
    {
        $this->expectException(\LogicException::class);
        $aggregation = new HistogramAggregation('foo');
        $aggregation->getArray();
    }

    /**
     * Tests if ChildrenAggregation#getArray throws exception when expected.
     */
    public function testGetArrayExceptionWhenDontSendInterval()
    {
        $this->expectException(\LogicException::class);
        $aggregation = new HistogramAggregation('foo', 'age');
        $aggregation->getArray();
    }

    /**
     * Tests getType method.
     */
    public function testHistogramAggregationGetType()
    {
        $aggregation = new HistogramAggregation('foo');
        $result = $aggregation->getType();
        $this->assertEquals('histogram', $result);
    }

    /**
     * Tests getArray method.
     */
    public function testChildrenAggregationGetArray()
    {
        $mock = $this->getMockBuilder(AbstractAggregation::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $aggregation = new HistogramAggregation('foo');
        $aggregation->addAggregation($mock);
        $aggregation->setField('age');
        $aggregation->setInterval(10);
        $result = $aggregation->getArray();
        $expected = ['field' => 'age', 'interval' => 10];
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests getArray method.
     */
    public function testIntervalGetArray()
    {
        $aggregation = new HistogramAggregation('foo');
        $aggregation->setField('age');
        $aggregation->setInterval(10);
        $result = $aggregation->getArray();
        $expected = ['field' => 'age', 'interval' => 10];
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests getArray method.
     */
    public function testExtendedBoundsGetArray()
    {
        $aggregation = new HistogramAggregation('foo');
        $aggregation->setField('age');
        $aggregation->setInterval(10);
        $aggregation->setExtendedBounds(0, 100);
        $result = $aggregation->getArray();
        $expected = ['field' => 'age', 'interval' => 10, 'extended_bounds' => ['min' => 0, 'max' => 100]];
        $this->assertEquals(['min' => 0, 'max' => 100], $aggregation->getExtendedBounds());
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests getArray method.
     */
    public function testExtendedBoundsWithNullGetArray()
    {
        $aggregation = new HistogramAggregation('foo');
        $aggregation->setField('age');
        $aggregation->setInterval(10);
        $aggregation->setExtendedBounds(0, null);
        $result = $aggregation->getArray();
        $expected = ['field' => 'age', 'interval' => 10, 'extended_bounds' => ['min' => 0]];
        $this->assertEquals(['min' => 0], $aggregation->getExtendedBounds());
        $this->assertEquals($expected, $result);
    }
}
