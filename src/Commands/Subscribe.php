<?php

declare(strict_types=1);

namespace Esplora\Analytics\Commands;

use Esplora\Analytics\Esplora;
use Esplora\Analytics\Models\Visit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\SignalableCommandInterface;

class Subscribe extends Command implements SignalableCommandInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esplora:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

    /**
     * @var array
     */
    protected $batch = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \JsonException
     */
    public function handle()
    {
        Esplora::redis()->subscribe(Esplora::VISITS_CHANNEL, function ($message) {
            $data = json_decode($message, true, 512, JSON_THROW_ON_ERROR);

            $this->batch[] = (new Visit($data))->toArray();

            if (count($this->batch) > config('esplora.batch')) {
                $this->saveVisits();
            }
        });

        return 0;
    }

    /**
     * Get the list of signals handled by the command.
     *
     * @return array
     */
    public function getSubscribedSignals(): array
    {
        return [SIGINT, SIGTERM, SIGHUP, SIGUSR1];
    }

    /**
     * Handle an incoming signal.
     *
     * @param int $signal
     *
     * @return void
     */
    public function handleSignal(int $signal): void
    {
        $this->saveVisits();
    }

    /**
     *
     */
    protected function saveVisits(): void
    {
        try {
            Visit::insert($this->batch);
        } catch (\Exception $exception) {
            Log::alert('Missing tracking batch');
        } finally {
            $this->batch = [];
        }
    }

}
