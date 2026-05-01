<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['restaurant_id', 'plan_id', 'status', 'starts_at', 'ends_at'];
}
