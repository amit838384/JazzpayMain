<?php

namespace App\Models\ManageCafe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishCategory_Model extends Model
{
    use HasFactory;

	protected $table = 'tbl_dish_category';

    protected $fillable = [
        'id',
        'cafeteria_id',
        'name',
        'meal_type',
        'status',
        'view',
        'created_at',
        'updated_at'
    ];
	
	
}
