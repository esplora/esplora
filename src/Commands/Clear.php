<?php

declare(strict_types=1);

namespace Esplora\Analytics\Commands;

use Illuminate\Console\Command;
use Esplora\Analytics\Models\Visit;

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
     * @return mixed
     * @throws \JsonException
     */
    public function handle()
    {
        Visit::truncate();

        return 0;
    }
}
