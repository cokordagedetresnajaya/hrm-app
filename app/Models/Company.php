<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory;
    protected $table = "companies";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        "name",
        "email",
        "logo",
        "website"
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "company_user", "company_id", "user_id");
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, "company_id", "id");
    }

    public function designations(): mixed
    {
        return $this->throughDepartments()->hasDesignations();
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-logo.png');
    }

    public function scopeForUser($query)
    {
        return $query->whereHas('users', function($q) {
            $q->where('id', Auth::user()->id);
        });
    }
}
