<?php

namespace App\Models\Admin\Aboutus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aboutus_Banner extends Model
{
    use HasFactory;

    protected $table = 'tbl_aboutus_banner';

    protected $fillable = [
       'id',  
       'image',   
       'title_one',   
       'title_two',   
       'description',   
       'status',  
       'created_at',  
       'updated_at'  

    ];
}
