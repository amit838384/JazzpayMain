<?php

namespace App\Models\ManageCafe;

use Illuminate\Database\Eloquent\Model;

class MenuAddonDate_Model extends Model
{
    protected $table = 'tbl_menu_addon_dates';

    protected $fillable = [
        'menu_addon_id', 'available_date',
    ];

    public function addon()
    {
        return $this->belongsTo(MenuAddon_Model::class, 'menu_addon_id');
    }
}