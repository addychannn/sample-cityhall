<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'content',
    ];

    protected $hidden = [
        'id'
    ];
    protected $appends = [
        'hash'
    ];
}
