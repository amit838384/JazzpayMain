<?php

namespace App\Models\PayForService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Plan_Model extends Model
{
    protected $table = 'tbl_plans';
    protected $fillable = [
        'id',
        'cafeteria_id',
        'cafeteria_user_id',
        'name',            
        'duration_days',   
        'price',
        'meals',           
        'days_of_week',    
        'active',
        'auto_renew',      
        'created_at',
        'updated_at'
    ];

    public function cafeteria(){ return $this->belongsTo(User::class, 'cafeteria_id'); }
}
