<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    protected $fillable = [
        'employee_id',
        'designation_id',
        'start_date',
        'end_date',
        'rate',
        'rate_type'
    ];

    protected $appends = ["duration"];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, "employee_id", "id")->withTrashed();
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, "designation_id", "id");
    }

    public function scopeInCompany(Builder $builder): void
    {
        $builder->whereHas("designation", function($q){
            $q->inCompany();
        });
    }

    public function getDurationAttribute()
    {
        return Carbon::parse($this->start_date)->diffForHumans($this->end_date);
    }

    public function scopeSearchByEmployee(Builder $builder, $name): void
    {
        $builder->whereHas("employee", function($q) use ($name) {
            $q->where("name", "like", "%" . $name . "%");
        });
    }

    public function getTotalEarnings($monthYear)
    {
        return $this->rate_type == "monthly" ? $this->rate : $this->rate * Carbon::parse($monthYear)->daysInMonth();
    }
}
