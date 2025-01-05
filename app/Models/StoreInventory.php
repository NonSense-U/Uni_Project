<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInventory extends Model
{
    /** @use HasFactory<\Database\Factories\StoreInventoryFactory> */
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
