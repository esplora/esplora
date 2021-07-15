<?php

declare(strict_types=1);

namespace Esplora\Analytics\Tests;

use Esplora\Analytics\Esplora;
use Esplora\Analytics\Models\Visit;
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

        $this->assertCount(1, Visit::all());
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


        $visit = Visit::find($id);

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

        $visits = Visit::where('url', $route)->get();

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

        $visits = Visit::where('referer', $referer)->get();

        $this->assertCount(1, $visits);
    }
}
