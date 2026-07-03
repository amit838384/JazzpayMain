<?php

namespace App\Models\Cafeteria;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafeteria_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_cafeteria';

    protected $fillable = [
       'id',  
       'school_id',   
       'cafeteria_name',   
       'address',   
       'view',  
       'created_at',  
       'updated_at'  

    ];
}
