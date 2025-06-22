<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "name",
        "company_id"
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, "company_id", "id");
    }

    public function designations(): HasMany
    {
        return $this->hasMany(Designation::class, "department_id", "id");
    }

    public function employees(): mixed
    {
        return $this->throughDesignations()->hasEmployees();
    }

    public function scopeInCompany(Builder $builder): void
    {
        $builder->where("company_id", session("company_id"));
    }
}
