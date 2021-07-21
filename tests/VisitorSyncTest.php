<?php

declare(strict_types=1);

namespace Esplora\Tracker\Tests;

use Esplora\Tracker\Esplora;
use Esplora\Tracker\Models\Visitor;
use Esplora\Tracker\Models\VisitorUrl;
use Illuminate\Support\Str;

class VisitorSyncTest extends TestCase
{
    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        config()->set('esplora.rules', []);
        config()->set('esplora.filling', 'sync');
    }

    /**
     *
     */
    public function testVisitor(): void
    {
        $this->startSession()->get('visit')
            ->assertOk();

        $this->assertCount(1, Visitor::all());
    }

    /**
     *
     */
    public function testIdVisitor(): void
    {
        $id = Str::orderedUuid();

        $this->session([
            Esplora::ID_SESSION => $id,
        ])
            ->get('visit')
            ->assertOk();


        $visit = Visitor::find($id);

        $this->assertNotNull($visit);
    }

    /**
     *
     */
    public function testUrlVisitor(): void
    {
        $route = route('visit', [
            'string' => Str::random(),
        ]);

        $this->get($route)->assertOk();
        $visits = Visitor::all();

        $this->assertCount(1, $visits);
    }

    /**
     *
     */
    public function testRefererVisitor(): void
    {
        $referer = 'https://orchid.software';

        $this->get(route('visit'), [
            'Referer' => $referer,
        ])->assertOk();

        $visits = VisitorUrl::where('referer', $referer)->get();

        $this->assertCount(1, $visits);
    }
}
