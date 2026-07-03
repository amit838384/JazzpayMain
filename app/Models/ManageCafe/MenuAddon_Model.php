<?php

namespace App\Models\ManageCafe;

use Illuminate\Database\Eloquent\Model;

class MenuAddon_Model extends Model
{
    protected $table = 'tbl_menu_addon';

    protected $fillable = [
        'dish_id', 'cafeteria_id', 'name', 'status',
    ];

    public function dates()
    {
        return $this->hasMany(MenuAddonDate_Model::class, 'menu_addon_id');
    }

    public function dish()
    {
        return $this->belongsTo(Dish_Model::class, 'dish_id');
    }

    public function cafeteria()
    {
        return $this->belongsTo(\App\Models\Cafeteria\Cafeteria_Model::class, 'cafeteria_id');
    }
}