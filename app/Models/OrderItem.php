<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'preparing', 'ready', 'served', 'cancelled'];
    public const DEPARTMENTS = ['kitchen', 'bar', 'other'];

    protected $fillable = ['restaurant_id', 'order_id', 'menu_item_id', 'name_snapshot', 'price_snapshot', 'quantity', 'notes', 'department', 'status'];

    protected $casts = ['price_snapshot' => 'decimal:2'];

    public function restaurant(): BelongsTo { return $this->belongsTo(Restaurant::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function menuItem(): BelongsTo { return $this->belongsTo(MenuItem::class); }
}
