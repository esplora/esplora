<?php

declare(strict_types=1);

namespace Esplora\Tracker\Tests;

use Esplora\Tracker\Esplora;
use Esplora\Tracker\Middleware\Tracking;
use Esplora\Tracker\Models\Visit;
use Illuminate\Support\Facades\Route;
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

    /**
     *
     */
    public function testResponseVisitor(): void
    {
        $this->get(route('visit'))->assertOk();

        $visits = Visit::where('response_code', 200)->get();

        $this->assertCount(1, $visits);
    }

    /**
     *
     */
    public function testErrorResponseVisitor(): void
    {
        Route::get('visit-error', fn () => abort(501))
            ->middleware(['web', Tracking::class])
            ->name('visit-error');

        $this->get(route('visit-error'));

        $visits = Visit::where('response_code', 501)->get();

        $this->assertCount(1, $visits);
    }

    /**
     *
     */
    public function testRedirectResponseVisitor(): void
    {
        Route::get('visit-redirect', fn () => response()->redirectTo('/redirect'))
            ->middleware(['web', Tracking::class])
            ->name('visit-redirect');

        $this->get(route('visit-redirect'))->assertRedirect('/redirect')->getStatusCode();

        $visits = Visit::where('response_code', 302)->get();

        $this->assertCount(1, $visits);
    }

    /**
     *
     */
    public function testTimeResponseVisitor(): void
    {
        $this->get(route('visit'));

        $visits = Visit::whereNull('response_time')->get();

        $this->assertCount(1, $visits);


        define('LARAVEL_START', microtime(true));

        $this->get(route('visit'));

        $visits = Visit::whereNotNull('response_time')->get();

        $this->assertCount(1, $visits);
    }
}
