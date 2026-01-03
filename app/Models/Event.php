<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'description',
        'start_date',
        'end_date',
    ];

    /**
     * =====================
     * RELATIONS
     * =====================
     */

    // Event bisa punya banyak tenant melalui pivot table tenant_event
    public function tenants()
    {
        return $this->belongsToMany(\App\Models\Tenant::class, 'tenant_event')
                    ->withPivot('start_date', 'end_date', 'active')
                    ->withTimestamps();
    }

    // Relasi ke interaksi (jika ada)
    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    /**
     * =====================
     * SCOPES (OPTIONAL)
     * =====================
     */

    // Event yang sedang aktif (dipakai untuk statistik periode event)
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', now());
        })->where(function ($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        });
    }
}