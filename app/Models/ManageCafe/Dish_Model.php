<?php

namespace App\Models\ManageCafe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish_Model extends Model
{
    use HasFactory;

	protected $table = 'tbl_dish';

    protected $fillable = [
        'id',
        'cafeteria_id',
        'dish_category_id',
        'ingredients_id',
        'dish_name',
        'description',
        'price',
        'serving_of',
        'calories',
        'protein',
        'carbohydrates',
        'fats',
        'image',
        'food_type',
        'status',
        'view',
        'created_at',
        'updated_at'
    ];
	
	public function category()
    {
        return $this->belongsTo(DishCategory_Model::class, 'dish_category_id');
    }
	
	public function addons()
    {
        return $this->hasMany(\App\Models\ManageCafe\MenuAddon_Model::class, 'dish_id');
    }
	
	
}
