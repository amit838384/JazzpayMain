<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_checkout';
        
    protected $fillable = [
        'id',
        'parent_id',
        'student_id',
        'school_id',
        'dish_id',
        'date',
        'qty',
        'dish_price',
        'total_price',
        'status',
        'view',
        'payment_type',
        'payment_status',
        'created_at',
        'updated_at'


    ];
}   

