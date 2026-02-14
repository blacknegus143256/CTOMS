<?php

namespace App\Models;

use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Attribute;
class TailoringShop extends Model implements HasName
{
    use HasFactory;
    protected $guarded = [];
    
    public function getFilamentName(): string
    {
        return $this->shop_name;
    }
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    protected $fillable = [
    'shop_name',
    'contact_person',
    'contact_role',
    'address',
    'contact_number',
];
    public function attributes(): BelongsToMany
    {
        // This tells Laravel: "I am connected to Attributes through the 'shop_attributes' table"
        return $this->belongsToMany(Attribute::class, 'shop_attributes')
            ->withPivot(['price', 'unit', 'notes', 'is_available']) // Let us access the extra columns
            ->withTimestamps();
    }

public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shop_user');
    }
}

