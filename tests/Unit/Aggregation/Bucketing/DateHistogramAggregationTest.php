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

use ONGR\ElasticsearchDSL\Aggregation\Bucketing\DateHistogramAggregation;
use ONGR\ElasticsearchDSL\Aggregation\AbstractAggregation;

/**
 * Unit test for children aggregation.
 */
class DateHistogramAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests if ChildrenAggregation#getArray throws exception when expected.
     */
    public function testGetArrayException()
    {
        $this->expectException(\LogicException::class);
        $aggregation = new DateHistogramAggregation('foo');
        $aggregation->getArray();
    }

    /**
     * Tests if ChildrenAggregation#getArray throws exception when expected.
     */
    public function testGetArrayExceptionWhenDontSendInterval()
    {
        $this->expectException(\LogicException::class);
        $aggregation = new DateHistogramAggregation('foo', 'date');
        $aggregation->getArray();
    }

    /**
     * Tests getType method.
     */
    public function testDateHistogramAggregationGetType()
    {
        $aggregation = new DateHistogramAggregation('foo');
        $result = $aggregation->getType();
        $this->assertEquals('date_histogram', $result);
    }

    /**
     * Tests getArray method.
     */
    public function testChildrenAggregationGetArray()
    {
        $mock = $this->getMockBuilder(AbstractAggregation::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $aggregation = new DateHistogramAggregation('foo');
        $aggregation->addAggregation($mock);
        $aggregation->setField('date');
        $aggregation->setCalendarInterval('month');
        $result = $aggregation->getArray();
        $expected = ['field' => 'date', 'calendar_interval' => 'month'];
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests getArray method.
     */
    public function testCalendarIntervalGetArray()
    {
        $aggregation = new DateHistogramAggregation('foo');
        $aggregation->setField('date');
        $aggregation->setCalendarInterval('month');
        $result = $aggregation->getArray();
        $expected = ['field' => 'date', 'calendar_interval' => 'month'];
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests getArray method.
     */
    public function testFixedIntervalGetArray()
    {
        $aggregation = new DateHistogramAggregation('foo');
        $aggregation->setField('date');
        $aggregation->setFixedInterval('month');
        $result = $aggregation->getArray();
        $expected = ['field' => 'date', 'fixed_interval' => 'month'];
        $this->assertEquals($expected, $result);
    }
}
