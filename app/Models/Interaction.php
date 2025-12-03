<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    protected $fillable = ['user_id', 'mood_id', 'menu_id'];

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
}
