<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, "company_id", "id");
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class, "payroll_id", "id");
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, "payroll_id", "id");
    }

    public function scopeInCompany(Builder $builder): void
    {
        $builder->where("company_id", session('company_id'));
    }

    public function scopePending(Builder $builder): void
    {
        $builder->whereHas('salaries', function ($q) {
            $q->whereDoesntHave('payment');
        });
    }

    public function getMonthYearAttribute(): string
    {
        return $this->year . "-" . $this->month;
    }

    public function getMonthStringAttribute()
    {
        return Carbon::parse($this->month_year)->format("F Y");
    }
}
