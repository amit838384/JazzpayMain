<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restrictedfoodbystudent_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_restricted_food_by_student';

    protected $fillable = [
        'id',
        'student_id',
        'ingredient_id',
        'name',
        'status',
        'view',
        'created_at  ',
        'updated_at' 

    ];
}   

