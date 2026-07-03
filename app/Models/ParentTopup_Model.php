<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentTopup_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_parents_topup';

    protected $fillable = [
        'id',
        'parent_id',
        'transaction_number',
        'amount',
        'payment_status',
        'is_processed',
        'status',
        'view',
        'created_at  ',
        'updated_at' 

    ];
}   

