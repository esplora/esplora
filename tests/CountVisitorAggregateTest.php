<?php

declare(strict_types=1);

namespace Esplora\Tracker\Tests;

use Carbon\Carbon;
use Esplora\Tracker\Aggregators\CountVisitorsAggregate;
use Esplora\Tracker\Models\EsploraAggregator;
use Esplora\Tracker\Models\Visitor;

class CountVisitorAggregateTest extends TestCase
{
    public function testZeroResult()
    {
        $aggregate = new CountVisitorsAggregate();
        $this->assertEquals(0, $aggregate->getCount());
    }

    public function testWhenWasNotRecords()
    {
        Visitor::factory()->count(3)->create();
        $aggregate = new CountVisitorsAggregate();
        $this->assertEquals(3, $aggregate->getCount());
    }

    public function testWhenWasRecordsBefore()
    {
        $timePointInPast = Carbon::now()->subHours(2);

        $factoryVisitor = Visitor::factory();

        // visitor who was aggregated
        $factoryVisitor->create([
            'created_at' => $timePointInPast
        ]);

        // new visitor
        $factoryVisitor->create([
            'created_at' => Carbon::now()
        ]);
        EsploraAggregator::factory()->count(4)->create([
            'key' => CountVisitorsAggregate::key(),
            'created_at' => $timePointInPast
        ]);
        $aggregate = new CountVisitorsAggregate();
        $this->assertEquals(1, $aggregate->getCount());
    }
}
