<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeAssignment extends Model
{
    protected $fillable = ['product_type_id', 'type_assignments_type', 'type_assignments_id', 'my_bonus_field'];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'type_assignments_id')->where('type_assignments_type', 'product');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'type_assignments_id')->where('type_assignments_type', 'category');
    }
}
