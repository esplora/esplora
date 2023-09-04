<?php

declare(strict_types=1);

namespace Esplora\Tracker\Tests;

use Esplora\Tracker\Esplora;
use Esplora\Tracker\Models\Visit;
use Illuminate\Support\Str;

class VisitorRedisTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('esplora.rules', []);
        config()->set('esplora.filling', 'redis');
    }

    public function testVisitor(): void
    {
        $this->startSession()->get('visit')->assertOk();

        $this->artisan('esplora:import')
            ->expectsOutput('Persistent storage recorded 1 visits and 0 goals.')
            ->assertExitCode(0);

        $this->assertCount(1, Visit::all());
    }

    public function testIdVisitor(): void
    {
        $id = Str::orderedUuid();

        $this->session([
            Esplora::ID_SESSION => $id,
        ])
            ->get('visit')
            ->assertOk();

        $this->artisan('esplora:import')
            ->expectsOutput('Persistent storage recorded 1 visits and 0 goals.')
            ->assertExitCode(0);

        $visit = Visit::find($id);

        $this->assertNotNull($visit);
    }
}
