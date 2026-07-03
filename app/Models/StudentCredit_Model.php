<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCredit_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_student_credit_transfer';

    protected $fillable = [
        'id',
        'student_id',
        'amount',
        'status',
        'view',
        'created_at',
        'updated_at' 

    ];
}   

