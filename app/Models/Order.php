<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Niche;
use App\Models\Store;
use App\Models\Supplier;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'order_date', 'supplier_id', 'niche_id', 'image_path', 'color', 'size',
        'status', 'note', 'main_days_allocated', 'extra_days_allocated',
        'days_spent_main', 'days_spent_extra', 'customer_name', 'email', 'country', 'quantity',
        'price', 'shipping_cost', 'discount', 'discount_percent', 'product_cost', 'note',
    ];

    protected $casts = [
        'order_date' => 'date',
        'shipping_cost' => 'float',
        'discount' => 'float',
        'discount_percent' => 'float',
        'product_cost' => 'float',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function niche()
    {
        return $this->belongsTo(Niche::class);
    }
}
