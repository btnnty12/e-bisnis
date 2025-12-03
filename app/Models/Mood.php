<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    protected $fillable = ['mood_name', 'description'];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }
}