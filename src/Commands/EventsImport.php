<?php

declare(strict_types=1);

namespace Esplora\Tracker\Commands;

use Esplora\Tracker\Esplora;
use Esplora\Tracker\Models\Goal;
use Esplora\Tracker\Models\Visitor;
use Illuminate\Console\Command;

class EventsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esplora:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Massively saves events from temporary storage to permanent';

    /**
     * @var Esplora
     */
    protected Esplora $esplora;

    /**
     * Create a new command instance.
     *
     * @param Esplora $esplora
     */
    public function __construct(Esplora $esplora)
    {
        $this->esplora = $esplora;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $goals = $this->esplora->importModelsForRedis(Goal::class);
        $visits = $this->esplora->importModelsForRedis(Visitor::class);

        $this->info("Persistent storage recorded $visits visits and $goals goals.");

        return 0;
    }
}
