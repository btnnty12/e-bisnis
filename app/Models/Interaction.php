<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interaction extends Model
{
    use HasFactory;

    /**
     * Interaction hanya menggunakan created_at
     * (tidak ada updated_at)
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'mood_id',
        'menu_id',
        'event_id',
        'session_id',
        'created_at',
    ];

    /**
     * =====================
     * RELATIONS
     * =====================
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * =====================
     * AUTO created_at
     * =====================
     */
    protected static function booted()
    {
        static::creating(function ($interaction) {
            if (!$interaction->created_at) {
                $interaction->created_at = now();
            }
        });
    }
}