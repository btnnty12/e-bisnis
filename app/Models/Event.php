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
    ];

    public function interactions()
    {
        return $this->hasMany(Interaction::class, 'event_id');
    }
}
