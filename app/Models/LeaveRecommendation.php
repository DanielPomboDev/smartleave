<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRecommendation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'leave_request_id',
        'decision',
        'reason',
        'department_admin_id',
    ];

    /**
     * Get the leave request that owns the recommendation.
     */
    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    /**
     * Get the department admin who made the recommendation.
     */
    public function departmentAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'department_admin_id', 'user_id');
    }
}
