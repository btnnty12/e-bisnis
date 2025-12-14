<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interaction extends Model
{
    use HasFactory;

    public $timestamps = false; // hanya ada created_at

    protected $fillable = ['user_id', 'mood_id', 'menu_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mood()
    {
        return $this->belongsTo(Mood::class, 'mood_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}