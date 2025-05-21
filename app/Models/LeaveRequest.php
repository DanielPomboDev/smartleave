<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    public const TYPE_VACATION = 'vacation';
    public const TYPE_SICK = 'sick';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DISAPPROVED = 'disapproved';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'leave_type',
        'subtype',
        'start_date',
        'end_date',
        'number_of_days',
        'where_spent',
        'commutation',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'commutation' => 'boolean',
        'number_of_days' => 'integer',
    ];

    /**
     * Available leave types with their display names.
     *
     * @var array<string, string>
     */
    public const LEAVE_TYPES = [
        self::TYPE_VACATION => 'Vacation Leave',
        self::TYPE_SICK => 'Sick Leave',
    ];

    /**
     * Available statuses with their display names.
     *
     * @var array<string, string>
     */
    public const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_DISAPPROVED => 'Disapproved',
    ];

    /**
     * Get the user that owns the leave request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the leave request is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the leave request is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if the leave request is disapproved.
     */
    public function isDisapproved(): bool
    {
        return $this->status === self::STATUS_DISAPPROVED;
    }
}
