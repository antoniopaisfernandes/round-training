<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramEditionSchedule extends Model
{
    use HasFactory;

    protected $casts = [
        'starts_at' => 'datetime:Y-m-d H:i',
        'ends_at' => 'datetime:Y-m-d H:i',
        'interval_start' => 'datetime:Y-m-d H:i',
    ];

    protected $guarded = [];

    public function programEdition(): BelongsTo
    {
        return $this->belongsTo(ProgramEdition::class);
    }

    /**
     * Calculates the working minutes for a schedule
     *
     * @return int
     */
    public function getWorkingMinutesAttribute()
    {
        return $this->ends_at->diffInMinutes($this->starts_at)
            - min($this->ends_at->diffInMinutes($this->interval_start), $this->interval_minutes);
    }

    /**
     * Calculates the working hours for a schedule
     *
     * @return int
     */
    public function getWorkingHoursAttribute()
    {
        return $this->getWorkingMinutesAttribute() / 60;
    }
}
