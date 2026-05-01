<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'vat_number', 'email', 'phone'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
