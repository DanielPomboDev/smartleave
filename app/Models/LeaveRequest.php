<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveRequest extends Model
{
    public const TYPE_VACATION = 'vacation';
    public const TYPE_SICK = 'sick';

    public const STATUS_PENDING = 'pending';
    public const STATUS_RECOMMENDED = 'recommended';
    public const STATUS_HR_APPROVED = 'hr_approved';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DISAPPROVED = 'disapproved';
    public const STATUS_CANCELLED = 'cancelled';

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
        'department_comments',
        'department_approved_by',
        'department_approved_at',
        'hr_comments',
        'hr_approved_by',
        'hr_approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'department_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
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
        self::STATUS_RECOMMENDED => 'Recommended',
        self::STATUS_HR_APPROVED => 'HR Approved',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_DISAPPROVED => 'Disapproved',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    /**
     * Get the user that owns the leave request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function recommendations()
    {
        return $this->hasMany(\App\Models\LeaveRecommendation::class, 'leave_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(\App\Models\LeaveApproval::class, 'leave_id');
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

    /**
     * Check if the leave request is HR approved.
     */
    public function isHrApproved(): bool
    {
        return $this->status === self::STATUS_HR_APPROVED;
    }

    /**
     * Check if the leave request is recommended.
     */
    public function isRecommended(): bool
    {
        return $this->status === self::STATUS_RECOMMENDED;
    }

    /**
     * Check if the leave request can be cancelled by the employee.
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_RECOMMENDED,
            self::STATUS_HR_APPROVED
        ]);
    }

    /**
     * Check if the leave request is fully approved (cannot be cancelled by employee).
     */
    public function isFullyApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if the leave request is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Cancel the leave request.
     */
    public function cancel(): bool
    {
        if (!$this->isCancellable()) {
            return false;
        }

        $this->status = self::STATUS_CANCELLED;
        return $this->save();
    }
}
