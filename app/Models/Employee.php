<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "email",
        "phone",
        "address",
        "designation_id"
    ];

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, "designation_id", "id");
    }

    public function department(): mixed
    {
        return $this->designation->department;
    }

    public function scopeInCompany(Builder $builder): void
    {
        $builder->whereHas("designation", function($q){
            $q->inCompany();
        });
    }

    public function scopeSearchByName(Builder $builder, $name): void
    {
        $builder->where("name", "like", "%" . $name . "%");
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class, "employee_id", "id");
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, "employee_id", "id");
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, "employee_id", "id");
    }

    public function getActiveContract($start_date = null, $end_date = null)
    {
        $start_date = $start_date ?? now();
        $end_date = $end_date ?? now();

        return $this->contracts()->where("start_date", ">=", $start_date)->where("end_date", "<=", $end_date)->first();
    }
}
