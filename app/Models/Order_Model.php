<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem_Model;

class Order_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_orders';
        
    protected $fillable = [
        'id',       
        'transaction_no',     
        'parent_id',      
        'student_id',     
        'school_id',      
        'cafeteria_id',       
        'date',       
        'total_amount',    
        'discount',       
        'after_discount',     
        'wallet_used',    
        'payable', 
        'grand_total',
        'payment_type',       
        'payment_status',     
        'created_at',     
        'updated_at'


    ];

    public function details()
{
    return $this->hasMany(OrderItem_Model::class, 'order_id');
}
}   

