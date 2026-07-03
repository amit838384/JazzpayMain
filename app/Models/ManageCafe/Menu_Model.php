<?php

namespace App\Models\ManageCafe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_menu';

    protected $fillable = [
        'id',
        'school_id',
        'cafeteria_id',
        'month',
        'year',
        'menu_upload',
        'status',
        'view',
        'created_at',
        'updated_at' 

    ];
}
