<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TailoringShop extends Model
{
    protected $fillable = [
    'shop_name',
    'contact_person',
    'contact_role',
    'address',
    'contact_number',
];
}
