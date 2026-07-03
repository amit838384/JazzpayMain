<?php

namespace App\Models\PayForService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use App\Models\PayForService\Plan_Model;
use App\Models\SchoolParent_Model;
use App\Models\SchoolStudent_Model;

class Subscription_Model extends Model
{
    protected $table = 'tbl_subscriptions';
    protected $fillable = [
        'id',
        'parent_id',
        'student_id',
        'school_id',
        'cafeteria_id',
        'plan_id',
        'start_date',      // date subscription begins
        'end_date',        // date subscription ends (inclusive)
        'duration_days',   // original duration
        'price',           // charged price
        'status',          // active, paused, cancelled, completed
        'remaining_days',  // integer
        'paused_days_count',
        'auto_renew',
        'subscription_count',
        'payment_status',  // paid, unpaid, refunded
        'created_at', 'updated_at'
    ];

    public function plan(){ return $this->belongsTo(Plan_Model::class, 'plan_id'); }
    public function parent(){ return $this->belongsTo(SchoolParent_Model::class, 'parent_id'); }
    public function student(){ return $this->belongsTo(SchoolStudent_Model::class, 'student_id'); }
    public function cafeteria(){ return $this->belongsTo(User::class, 'cafeteria_id'); }
    public function pauses(){ return $this->hasMany(SubscriptionPause_Model::class, 'subscription_id'); }
}