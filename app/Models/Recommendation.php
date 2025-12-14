<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'mood_id',
        'category_id',
        'score'
    ];

    //Relasi ke Mood
   public function mood()
    {
        return $this->belongsTo(Mood::class);
    }

   
    //Relasi ke Category
     
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}