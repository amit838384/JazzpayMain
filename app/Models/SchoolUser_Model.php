<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolUser_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_school_users';

    protected $fillable = [
        'id',  
        'school_id',
        'name',
        'email',
        'role',
        'invite_code',
        'status',
        'created_at',
        'updated_at'


    ];
}
