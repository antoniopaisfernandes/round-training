<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramEdition extends Model
{
    protected $guarded = [];

    public function program()
    {
        return $this->belongsToMany(Program::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function schedules()
    {
        return $this->hasMany(ProgramEditionSchedules::class);
    }
}
