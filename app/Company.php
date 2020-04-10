<?php

namespace App;

use App\Student;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
