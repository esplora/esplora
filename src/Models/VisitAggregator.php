<?php

declare(strict_types=1);

namespace Esplora\Tracker\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class VisitAggregator extends Model
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
    protected $table = 'esplora_aggregators';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'data',
        'start_at',
        'finish_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data'        => 'array',
        'start_at'    => 'datetime',
        'finish_at'   => 'datetime',
        'created_at'  => 'timestamp',
    ];
}
