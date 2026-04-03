<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyAdsEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'amount',
        'entry_date',
        'is_full_month',
        'note',
    ];

    protected $casts = [
        'amount' => 'float',
        'entry_date' => 'date',
        'is_full_month' => 'boolean',
    ];

    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->where('year', $year)->where('month', $month);
    }
}

