<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const SUPER_ADMIN = 'super_admin';
    public const OWNER = 'owner';
    public const MANAGER = 'manager';
    public const STAFF = 'staff';
    public const KITCHEN = 'kitchen';
    public const ACCOUNTANT = 'accountant';

    protected $fillable = ['name', 'slug'];
}
