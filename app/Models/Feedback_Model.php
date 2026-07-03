<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback_Model extends Model
{
    use HasFactory;

     protected $table = 'app_feedback';

    protected $fillable = [
        'id',
        'parent_id',
        'message',
        'status',
        'view',
        'created_at',
        'updated_at' 

    ];
}   

