<?php

namespace App;

use Illuminate\Console\Scheduling\ManagesFrequencies;
use Illuminate\Database\Eloquent\Model;

class ProgramEditionSchedule extends Model
{
    use ManagesFrequencies;

    protected $guarded = [];
}
