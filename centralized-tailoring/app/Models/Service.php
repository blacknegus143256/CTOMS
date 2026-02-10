<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    // This allows us to save all fields
    protected $guarded = [];

    // RELATIONSHIP: A Service belongs to ONE Shop
    public function tailoringShop(): BelongsTo
    {
        return $this->belongsTo(TailoringShop::class);
    }
}