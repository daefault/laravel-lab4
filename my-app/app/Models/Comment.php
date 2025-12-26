<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
        protected $fillable = [
        'content',
        'user_id',
        'character_id',
    ];

    protected $dates = ['deleted_at'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}