<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApproval extends Model
{
    protected $primaryKey = 'approval_id';

    protected $fillable = [
        'approval_id',
        'hr_manager_id',
        'leave_id',
        'approval',
        'approved_for',
        'dissapproved_due_to',
    ];

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_id');
    }

    public function hrManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hr_manager_id', 'user_id');
    }
}
