<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mood extends Model
{
    use HasFactory;

    protected $fillable = ['mood_name', 'description'];

    public function categories()
    {
        return $this->hasMany(Category::class, 'mood_id');
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class, 'mood_id');
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class, 'mood_id');
    }
}