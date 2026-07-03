<?php

namespace App\Models\PayForService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PayForService\Subscription_Model;

class SubscriptionPause_Model extends Model
{
    protected $table = 'tbl_subscription_pauses';
    protected $fillable = [
        'id',
        'subscription_id',
        'pause_date',    
        'requested_by',  
        'requested_at',  
        'approved_by',   
        'approved_at',
        'status',        
        'reason',        
        'created_at',
        'updated_at'
    ];

    public function subscription(){ return $this->belongsTo(Subscription_Model::class, 'subscription_id'); }
}
