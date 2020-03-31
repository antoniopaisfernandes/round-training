<?php

namespace App;

use Illuminate\Console\Scheduling\ManagesFrequencies;
use Illuminate\Database\Eloquent\Model;

class ProgramEditionSchedule extends Model
{
    use ManagesFrequencies;

    protected $casts = [
        'starts_at' => 'datetime:Y-m-d H:i',
        'ends_at' => 'datetime:Y-m-d H:i',
        'interval_start' => 'datetime:Y-m-d H:i',
    ];

    protected $guarded = [];
}
