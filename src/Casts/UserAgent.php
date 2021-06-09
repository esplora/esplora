<?php

declare(strict_types=1);

namespace Esplora\Analytics\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Jenssegers\Agent\Agent;

class UserAgent implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     *
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        $agent = new Agent();
        $agent->setUserAgent($value);

        return [
            'device'   => $this->detectDevice($agent),
            'platform' => $agent->platform(),
            'browser'  => $agent->browser(),
        ];
    }

    /**
     * @param Agent $agent
     *
     * @return string
     */
    private function detectDevice(Agent $agent): string
    {
        if ($agent->isBot()) {
            return 'bot';
        }

        if ($agent->isDesktop()) {
            return 'desktop';
        }

        if ($agent->isTablet()) {
            return 'tablet';
        }

        if ($agent->isPhone()) {
            return 'phone';
        }

        return 'unknown';
    }
}
