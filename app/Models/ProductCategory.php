<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = ['name', 'description', 'external_url'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

    public function typeAssignments(): HasMany
    {
        return $this->hasMany(TypeAssignment::class, 'type_assignments_id')->where('type_assignments_type', 'category');
    }
}
