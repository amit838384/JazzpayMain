<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_grade';

    protected $fillable = [
        'id',
        'grade',
        'status',
        'view',
        'created_at  ',
        'updated_at' 

    ];
}   

