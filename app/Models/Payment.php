<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'employee_id',
        'payroll_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, "payroll_id", "id");
    }

    public function scopeInCompany(Builder $builder)
    {
        $builder->whereHas("employee", function($q){
            $q->inCompany();
        });
    }
}
