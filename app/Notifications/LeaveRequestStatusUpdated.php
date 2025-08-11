<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = $this->leaveRequest->status === LeaveRequest::STATUS_DEPARTMENT_APPROVED ? 'approved' : 'disapproved';
        $employeeName = $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name;

        return (new MailMessage)
            ->subject('Leave Request ' . ucfirst($status))
            ->line('A leave request has been ' . $status . ' by the department head.')
            ->line('Employee: ' . $employeeName)
            ->line('Leave Type: ' . LeaveRequest::LEAVE_TYPES[$this->leaveRequest->leave_type])
            ->line('Period: ' . $this->leaveRequest->start_date->format('M d, Y') . ' to ' . $this->leaveRequest->end_date->format('M d, Y'))
            ->line('Number of Days: ' . $this->leaveRequest->number_of_days)
            ->line('Department Comments: ' . ($this->leaveRequest->department_comments ?: 'No comments provided'))
            ->action('View Leave Request', route('hr.leave.requests'))
            ->line('Please review this leave request for final approval.');
    }

    public function toArray($notifiable)
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'status' => $this->leaveRequest->status,
            'employee_name' => $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name,
            'leave_type' => LeaveRequest::LEAVE_TYPES[$this->leaveRequest->leave_type],
            'start_date' => $this->leaveRequest->start_date->format('Y-m-d'),
            'end_date' => $this->leaveRequest->end_date->format('Y-m-d'),
            'number_of_days' => $this->leaveRequest->number_of_days,
            'department_comments' => $this->leaveRequest->department_comments,
        ];
    }
}
