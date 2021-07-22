<?php

declare(strict_types=1);

namespace Esplora\Tracker\Models;

use Esplora\Tracker\Casts\UserAgent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;
    use MassPrunable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'esplora_visitors';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'ip',
        'device',
        'platform',
        'browser',
        'preferred_language',
        'user_agent',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'string',
        'ip'         => 'string',
        'user_agent' => UserAgent::class,
        'created_at' => 'timestamp',
    ];

    protected $hidden = [
        'user_agent',
    ];

    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    public function getConnectionName(): ?string
    {
        return config('esplora.connection');
    }

    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        return static::where('created_at', '<=', now()->subDays(config('esplora.pruning')));
    }

    public function urls()
    {
        return $this->hasMany(VisitorUrl::class);
    }
}
