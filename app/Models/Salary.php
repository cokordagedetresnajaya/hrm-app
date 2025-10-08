<?php

namespace App\Models;

use App\Services\NetPayCalculationsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    protected $fillable = [
        "payroll_id",
        "employee_id",
        "gross_salary"
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, "payroll_id", "id");
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }

    public function getBreakdownAttribute(): NetPayCalculationsService
    {
        return new NetPayCalculationsService($this->gross_salary, $this->employee->marital_status, $this->employee->dependents_count);
    }

    public function getDeductionAttribute()
    {
        return $this->breakdown->getDeductions();
    }

    public function getNetPayAttribute()
    {
        return $this->breakdown->calculateNetPay();
    }
}
