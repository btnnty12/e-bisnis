<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name', 'mood_id'];

    public function mood()
    {
        return $this->belongsTo(Mood::class, 'mood_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'category_id');
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class, 'category_id');
    }
}