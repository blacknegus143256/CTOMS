<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeCategory extends Model
{
    protected $guarded = []; // Allows us to create categories easily

    // A Category (Fabrics) has many Attributes (Silk, Cotton, Linen)
    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}