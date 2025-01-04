<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /** @use HasFactory<\Database\Factories\StoreFactory> */
    use HasFactory;

    public function owner()
    {
        return $this->belongsTo(StoreOwner::class);
    }

    public function inventories()
    {
        return $this->hasMany(StoreInventory::class);
    }

}
