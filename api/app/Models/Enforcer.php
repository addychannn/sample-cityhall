<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enforcer extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'title',
        'content'
    ];

}
