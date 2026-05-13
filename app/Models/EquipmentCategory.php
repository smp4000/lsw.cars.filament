<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentCategory extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(EquipmentItem::class, 'category_id')->orderBy('sortierung')->orderBy('name');
    }
}
