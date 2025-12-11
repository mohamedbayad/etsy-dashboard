<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Store;
use App\Models\Supplier;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'order_date', 'supplier_id', 'image_path', 'color', 'size',
        'status', 'note', 'main_days_allocated', 'extra_days_allocated',
        'days_spent_main', 'days_spent_extra'
    ];

    protected $casts = [
        'order_date' => 'date',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
