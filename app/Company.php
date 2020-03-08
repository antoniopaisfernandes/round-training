<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
