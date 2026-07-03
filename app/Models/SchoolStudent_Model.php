<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolParent_Model;


class SchoolStudent_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_school_student';

    protected $fillable = [
        'id',
        'parent_id',
        'school_id',
        'student_name',
        'grade',
        'gender',
        'dob',
        'wallet_balance',
        'spend_limit',
        'verified',
        'image',
        'status',
        'view',
        'created_at',
        'updated_at',  


    ];

    public function parent()
    {
        return $this->belongsTo(SchoolParent_Model::class, 'parent_id', 'id');
    }
}
