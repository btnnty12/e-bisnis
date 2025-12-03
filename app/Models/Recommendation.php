<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = ['mood_id', 'category_id', 'score'];

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
