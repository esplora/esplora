<?php

declare(strict_types=1);

namespace Esplora\Tracker\Tests\Aggregators;

use Carbon\Carbon;
use Esplora\Tracker\Aggregators\VisitorBrowsersAggregate;
use Esplora\Tracker\Models\EsploraAggregator;
use Esplora\Tracker\Models\Visitor;
use Esplora\Tracker\Presenters\VisitorBrowsersPresenter;
use Esplora\Tracker\Tests\TestCase;

class VisitorBrowsersAggregateTest extends TestCase
{
    public function testPresenter()
    {
        Visitor::factory()->count(2)->create();
        $aggregator = new VisitorBrowsersAggregate();
        $this->assertInstanceOf(VisitorBrowsersPresenter::class, $aggregator->presenter());

        $this->assertJsonDocumentMatchesSchema($aggregator->presenter()->toJson(), [
            'type'       => 'array',
            'items' => [
                'type' => 'object',
                'required' => ['browser', 'count'],
                'properties' =>  [
                    'browser' => [
                        'type' => 'string'
                    ],
                    'count' => [
                        'type' => 'number'
                    ]
                ],
            ],
        ]);
    }

    public function testValidCountBrowsers()
    {
        Visitor::factory()->create([
            'browser' => 'Chrome'
        ]);
        Visitor::factory()->create([
            'browser' => 'Firefox'
        ]);

        $aggregator = new VisitorBrowsersAggregate();
        $json = $aggregator->presenter()->toJson();

        $this->assertJsonValueEquals($json, '0.browser', 'Chrome');
        $this->assertJsonValueEquals($json, '1.browser', 'Firefox');
        $this->assertJsonValueEquals($json, '0.count', '1');
        $this->assertJsonValueEquals($json, '1.count', '1');
    }

    public function testBrowsersInTimeInterval()
    {
        $pointInPast = Carbon::now()->subHours(10);

        // old visitor
        Visitor::factory()->create([
            'browser' => 'Chrome',
            'created_at' => $pointInPast
        ]);

        // fresh visitor
        Visitor::factory()->create([
            'browser' => 'Chrome',
            'created_at' => Carbon::now()
        ]);

        EsploraAggregator::factory()->count(1)->create([
            'key'        => VisitorBrowsersAggregate::key(),
            'created_at' => $pointInPast,
        ]);

        $aggregator = new VisitorBrowsersAggregate();
        $json = $aggregator->presenter()->toJson();
        $this->assertJsonValueEquals($json, '0.browser', 'Chrome');
        $this->assertJsonValueEquals($json, '0.count', '1');
        $this->assertJsonDocumentMatchesSchema($aggregator->presenter()->toJson(), [
            'type'       => 'array',
            'minItems' =>  1,
            'maxItems' =>  1,
        ]);
    }
}
