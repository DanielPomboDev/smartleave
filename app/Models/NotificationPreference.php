<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'email_leave_requests',
        'email_approvals',
        'email_rejections',
        'in_app_leave_requests',
        'in_app_approvals',
        'in_app_rejections',
        'push_leave_requests',
        'push_approvals',
        'push_rejections',
    ];

    protected $casts = [
        'email_leave_requests' => 'boolean',
        'email_approvals' => 'boolean',
        'email_rejections' => 'boolean',
        'in_app_leave_requests' => 'boolean',
        'in_app_approvals' => 'boolean',
        'in_app_rejections' => 'boolean',
        'push_leave_requests' => 'boolean',
        'push_approvals' => 'boolean',
        'push_rejections' => 'boolean',
    ];

    /**
     * Get the user that owns this notification preference.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
