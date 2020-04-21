<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyYearlyBudget extends Model
{
    protected $fillable = ['company_id', 'year', 'budget'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
