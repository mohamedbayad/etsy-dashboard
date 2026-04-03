<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Niche extends Model
{
    use HasFactory;

    public const STATUS_CONNECTED = 'connected';
    public const STATUS_INVALID = 'invalid';
    public const STATUS_MISSING = 'missing';
    public const STATUS_UNREACHABLE = 'unreachable';
    public const STATUS_ERROR = 'error';
    public const STATUS_UNCHECKED = 'unchecked';

    protected $fillable = [
        'name',
        'slug',
        'sheet_url',
        'sheet_id',
        'sheet_status',
        'sheet_last_checked_at',
        'sheet_error_message',
    ];

    protected $casts = [
        'sheet_last_checked_at' => 'datetime',
    ];

    public static function allowedSheetStatuses(): array
    {
        return [
            self::STATUS_CONNECTED,
            self::STATUS_INVALID,
            self::STATUS_MISSING,
            self::STATUS_UNREACHABLE,
            self::STATUS_ERROR,
            self::STATUS_UNCHECKED,
        ];
    }

    public function getSheetStatusLabelAttribute(): string
    {
        return match ($this->sheet_status) {
            self::STATUS_CONNECTED => 'Connected',
            self::STATUS_INVALID => 'Invalid',
            self::STATUS_MISSING => 'Missing',
            self::STATUS_UNREACHABLE => 'Unreachable',
            self::STATUS_ERROR => 'Error',
            default => 'Unchecked',
        };
    }

    public function getSheetStatusBadgeClassAttribute(): string
    {
        return match ($this->sheet_status) {
            self::STATUS_CONNECTED => 'border-green-200 bg-green-100 text-green-800',
            self::STATUS_MISSING => 'border-slate-200 bg-slate-100 text-slate-700',
            self::STATUS_UNCHECKED => 'border-yellow-200 bg-yellow-100 text-yellow-800',
            self::STATUS_INVALID, self::STATUS_UNREACHABLE, self::STATUS_ERROR => 'border-red-200 bg-red-100 text-red-800',
            default => 'border-slate-200 bg-slate-100 text-slate-700',
        };
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
