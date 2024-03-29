<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'orders';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderItem() {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}
