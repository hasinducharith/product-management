<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    protected $fillable = ['name', 'api_unique_number'];

    public function typeAssignments(): HasMany
    {
        return $this->hasMany(TypeAssignment::class, 'product_type_id');
    }
}
