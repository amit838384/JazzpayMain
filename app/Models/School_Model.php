<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_school';

    protected $fillable = [
        'id',
        'school_name',
        'address',
        'status',
        'view',
        'created_at  ',
        'updated_at' 

    ];
}   

