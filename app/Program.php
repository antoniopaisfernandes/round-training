<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $guarded = [];

    public function editions()
    {
        return $this->hasMany(ProgramEdition::class);
    }
}
