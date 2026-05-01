<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'slug', 'price_monthly', 'max_users', 'max_locations'];
}
