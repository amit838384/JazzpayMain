<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertMessage_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_alert_message';

    protected $fillable = [
        'id',
        'message',
        'status',
        'view',
        'created_at  ',
        'updated_at' 

    ];
}   

