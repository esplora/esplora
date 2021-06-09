<?php

declare(strict_types=1);

namespace Esplora\Analytics\Models;

use Esplora\Analytics\Casts\UserAgent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

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
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'url',
        'ip',
        'device',
        'platform',
        'browser',
        'preferred_language',
        'user_agent',
        'referer',
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
        'referer'    => 'string',
        'user_agent' => UserAgent::class,
        'url'        => 'string',
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
}
