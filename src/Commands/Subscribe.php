<?php

declare(ticks=1, strict_types=1);

namespace Mircurius\Analytics\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Mircurius\Analytics\Models\Visit;

class Subscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mircurius:subscribe';

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
        $this->registerSignalHandler();
        $this->registerRedisHandler();

        return 0;
    }

    /**
     *
     */
    protected function registerSignalHandler(): void
    {
        $signalHandler = function () {
            $this->saveVisits();
        };

        pcntl_signal(SIGTERM, $signalHandler);
        pcntl_signal(SIGHUP, $signalHandler);
        pcntl_signal(SIGUSR1, $signalHandler);
    }

    /**
     * @throws \JsonException
     */
    protected function registerRedisHandler(): void
    {
        Redis::subscribe('visits-channel', function ($message) {
            $this->batch[] = json_decode($message, true, 512, JSON_THROW_ON_ERROR);

            if (count($this->batch) > 10) {
                $this->saveVisits();
            }
        });
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
