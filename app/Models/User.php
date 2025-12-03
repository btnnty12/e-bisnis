<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'tenant_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==============================
    // RELATIONS
    // ==============================

    // User tenant (khusus role: tenant)
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Interaksi user -> menu
    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }
}