<?php

declare(strict_types=1);

namespace Esplora\Analytics\Commands;

use Esplora\Analytics\Models\Visit;
use Illuminate\Console\Command;

class Clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esplora:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the history of recent visits';

    /**
     * Execute the console command.
     *
     * @throws \JsonException
     *
     * @return mixed
     */
    public function handle()
    {
        Visit::truncate();

        return 0;
    }
}
