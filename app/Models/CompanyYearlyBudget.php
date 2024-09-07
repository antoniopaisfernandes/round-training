<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyYearlyBudget extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'year', 'budget'];

    protected $casts = [
        'budget' => 'double',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getExecutionAttribute()
    {
        if (! $this->budget) {
            return null;
        }

        return $this->company->executedCostsInYear($this->year) / $this->budget;
    }
}
