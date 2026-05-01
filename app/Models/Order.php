<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['open', 'sent_to_kitchen', 'preparing', 'ready', 'served', 'paid', 'cancelled'];

    protected $fillable = ['restaurant_id', 'table_id', 'booking_id', 'order_number', 'status', 'notes', 'opened_by', 'closed_at'];

    protected $casts = ['closed_at' => 'datetime'];

    public function restaurant(): BelongsTo { return $this->belongsTo(Restaurant::class); }
    public function table(): BelongsTo { return $this->belongsTo(Table::class); }
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function opener(): BelongsTo { return $this->belongsTo(User::class, 'opened_by'); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }

    public function total(): float
    {
        return (float) $this->items->sum(fn (OrderItem $item) => ($item->status === 'cancelled' ? 0 : $item->price_snapshot * $item->quantity));
    }
}
