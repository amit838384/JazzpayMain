<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Order_Model;

class OrderItem_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_order_items';
        
    protected $fillable = [
        'id',
        'order_id',
        'dish_id',
        'qty',
        'dish_price',
        'total_price',
        'created_at',
        'updated_at'


    ];

    public function order()
{
    return $this->belongsTo(Order_Model::class, 'order_id');
}
}   

