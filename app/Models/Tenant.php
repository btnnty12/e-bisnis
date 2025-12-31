<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = ['tenant_name', 'location', 'start_date', 'end_date'];

    // Cast tanggal agar otomatis menjadi Carbon instance
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /**
     * Relasi ke Menu
     */
    public function menus()
    {
        return $this->hasMany(Menu::class, 'tenant_id');
    }

    /**
     * Relasi ke User
     */
    public function users()
    {
        return $this->hasMany(User::class, 'tenant_id');
    }

    /**
     * Relasi ke Event (many-to-many dengan pivot `active`)
     */
    public function events()
{
    return $this->belongsToMany(Event::class, 'tenant_event') // ✅ sesuai migration
                ->withPivot('start_date', 'end_date', 'active') // include semua pivot fields
                ->withTimestamps();
}

    /**
     * Scope untuk tenant/event yang aktif hari ini berdasarkan tanggal tenant
     */
    public function scopeActive($query)
    {
        $today = now()->format('Y-m-d');
        return $query->where('start_date', '<=', $today)
                     ->where('end_date', '>=', $today);
    }

    /**
     * Check apakah tenant/event sedang aktif hari ini berdasarkan tanggal tenant
     */
    public function isActive(): bool
    {
        $today = now()->format('Y-m-d');
        return $this->start_date && $this->end_date &&
               $today >= $this->start_date->format('Y-m-d') &&
               $today <= $this->end_date->format('Y-m-d');
    }
}