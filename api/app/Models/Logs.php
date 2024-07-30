<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'actor',
        'action',
        'type',
        'level',
        'old_data',
        'new_data',
        'module',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
