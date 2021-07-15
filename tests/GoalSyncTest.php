<?php

declare(strict_types=1);

namespace Esplora\Analytics\Tests;

use Esplora\Analytics\Models\Goal;

class GoalSyncTest extends TestCase
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
    public function testGoals(): void
    {
        $this->get('goal')->assertOk();

        $this->assertCount(1, Goal::all());
    }
}
