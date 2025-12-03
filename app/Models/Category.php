<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_name', 'mood_id'];

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
