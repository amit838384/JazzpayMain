<?php

namespace App\Models\ManageCafe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_meal';

    protected $fillable = [
        'id',  
        'name',
        'status',
        'view',
        'created_at',
        'updated_at',


    ];
}
