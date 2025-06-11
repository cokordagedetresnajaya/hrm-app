<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designation extends Model
{
    protected $fillable = [
        "name",
        "department_id"
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, "department_id", "id");
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, "designation_id", "id");
    }

    public function scopeInCompany(Builder $builder): void
    {
        $builder->whereHas("department", function($q) {
            return $q->inCompany();
        });
    }
}
