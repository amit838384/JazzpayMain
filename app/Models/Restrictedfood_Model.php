<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restrictedfood_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_restricted_food';

    protected $fillable = [
        'id',
        'food_id',
        'parent_id',
        'name',
        'status',
        'view',
        'created_at  ',
        'updated_at' 

    ];
}   

