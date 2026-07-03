<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class SchoolParent_Model extends Authenticatable implements JWTSubject
{
    use Notifiable;

     protected $table = 'tbl_school_parents';

    protected $fillable = [
        'id',
        'school_id',
        'name',
        'mobile',
        'email',
        'role',
        'invite_code',
        'sent_date',
        'accepted_date',
        'balance',
        'status',
        'view',
        'created_at',  
        'updated_at',


    ];


    public function getJWTIdentifier()
    {
        return $this->getKey(); 
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
