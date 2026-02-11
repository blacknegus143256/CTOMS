<?php

namespace App\Models;

use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class TailoringShop extends Model implements HasName
{
    use HasFactory;
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

public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shop_user');
    }
}

