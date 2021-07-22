<?php

declare(strict_types=1);

namespace Esplora\Tracker\Tests;

use Esplora\Tracker\Aggregators\CountVisitorsAggregate;

class CountVisitorAggregateTest extends TestCase
{
    public function testZeroResult()
    {
        $aggregate = new CountVisitorsAggregate();
        $this->assertEquals(0, $aggregate->getCount());
    }

    public function testIsRightCountVisited()
    {
    }
}
