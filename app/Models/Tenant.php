<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Tenant extends Model
{
    use HasFactory;

    // =========================
    // MASS ASSIGNMENT
    // =========================
    protected $fillable = [
        'tenant_name',
        'location',
        'start_date',
        'end_date',
    ];

    // =========================
    // CASTS
    // =========================
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date'   => 'date:Y-m-d',
    ];


    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'tenant_event')
            ->withPivot([
                'start_date',
                'end_date',
                'active',
            ])
            ->withTimestamps();
    }

    // =========================
    // TENANT GLOBAL (BUKAN EVENT)
    // =========================

    /**
     * Scope tenant aktif GLOBAL
     * (jarang dipakai kalau sistem event)
     */
    public function scopeActive($query)
    {
        $today = Carbon::today();

        return $query
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);
    }

    /**
     * Check tenant aktif GLOBAL
     */
    public function isActive(): bool
    {
        if (!$this->start_date || !$this->end_date) {
            return false;
        }

        $today = Carbon::today();

        return $today->between(
            $this->start_date,
            $this->end_date
        );
    }

    // =========================
    // TENANT DI EVENT (INI YANG DIPAKAI)
    // =========================

    /**
     * Check tenant aktif DI EVENT
     * ✔️ pakai pivot tenant_event
     * ✔️ tanggal + active sinkron database
     */
    public function isActiveInEvent(int $eventId): bool
    {
        $event = $this->events
            ->where('id', $eventId)
            ->first();

        if (!$event || !$event->pivot) {
            return false;
        }

        if ((int) $event->pivot->active !== 1) {
            return false;
        }

        $today = Carbon::today();

        return $today->between(
            Carbon::parse($event->pivot->start_date),
            Carbon::parse($event->pivot->end_date)
        );
    }
}