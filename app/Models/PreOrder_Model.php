<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ManageCafe\Dish_Model;
use App\Models\User;
use App\Models\SchoolStudent_Model;


class PreOrder_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_preorders';
        
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
        'mail_sent',
        'cafeteria_id',
        'addons',
        'transaction_no',
        'created_at',
        'updated_at'


    ];

    public function dish()
{
    return $this->belongsTo(Dish_Model::class, 'dish_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'cafeteria_id', 'cafeteria_id');
}

public function student() {
    return $this->belongsTo(SchoolStudent_Model::class, 'student_id');
}

}   

