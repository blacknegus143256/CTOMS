<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribute extends Model
{
    use HasFactory;
    protected $guarded = [];
    

    public function category(): BelongsTo
    {
        return $this->belongsTo(AttributeCategory::class, 'attribute_category_id');
    }
    
    public function tailoringShops(): BelongsToMany
    {
        // This tells Laravel: "I am connected to TailoringShops through the 'shop_attributes' table"
        return $this->belongsToMany(TailoringShop::class, 'shop_attributes')
            ->withPivot(['price', 'unit', 'notes', 'is_available']) // Let us access the extra columns
            ->withTimestamps();
    }
}
