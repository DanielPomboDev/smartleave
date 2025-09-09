<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestStatusUpdated extends Notification
{
    use Queueable;

    protected $leaveRequest;
    protected $notificationType;

    public function __construct(LeaveRequest $leaveRequest, $notificationType = 'status_update')
    {
        $this->leaveRequest = $leaveRequest;
        $this->notificationType = $notificationType;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $employeeName = $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name;
        
        // Customize message based on notification type and user role
        if ($this->notificationType === 'new_request' && $notifiable->user_type === 'department_admin') {
            $subject = 'New Leave Request Submitted';
            $line1 = 'A new leave request has been submitted by ' . $employeeName . '.';
            $line2 = 'Please review this request in your department dashboard.';
            $actionText = 'Review Leave Request';
            $route = route('department.leave.requests');
        } elseif ($this->notificationType === 'recommended' && $notifiable->user_type === 'hr') {
            $subject = 'Leave Request Recommended for Approval';
            $line1 = 'A leave request from ' . $employeeName . ' has been recommended by their department head.';
            $line2 = 'Please review this request for HR approval.';
            $actionText = 'Review Leave Request';
            $route = route('hr.leave.requests');
        } elseif ($this->notificationType === 'recommended' && $notifiable->user_type === 'employee') {
            $subject = 'Leave Request Recommended';
            $line1 = 'Your leave request has been recommended by your department head.';
            $line2 = 'It is now pending HR approval.';
            $actionText = 'View Leave Request';
            $route = route('employee.leave.history');
        } elseif ($this->notificationType === 'disapproved' && $notifiable->user_type === 'employee') {
            $subject = 'Leave Request Disapproved by Department';
            $line1 = 'Your leave request has been disapproved by your department head.';
            $line2 = 'Please check the comments provided by the approver.';
            $actionText = 'View Leave Request';
            $route = route('employee.leave.history');
        } elseif ($this->notificationType === 'hr_approved' && $notifiable->user_type === 'mayor') {
            $subject = 'Leave Request Approved by HR';
            $line1 = 'A leave request from ' . $employeeName . ' has been approved by HR.';
            $line2 = 'Please review this request for final approval.';
            $actionText = 'Review Leave Request';
            $route = route('mayor.leave.requests');
        } elseif ($this->notificationType === 'hr_approved' && $notifiable->user_type === 'employee') {
            $subject = 'Leave Request Approved by HR';
            $line1 = 'Your leave request has been approved by HR.';
            $line2 = 'It is now pending final approval.';
            $actionText = 'View Leave Request';
            $route = route('employee.leave.history');
        } elseif ($this->notificationType === 'approved' && $notifiable->user_type === 'employee') {
            $subject = 'Leave Request Approved';
            $line1 = 'Your leave request has been approved.';
            $line2 = 'You can now proceed with your leave as scheduled.';
            $actionText = 'View Leave Request';
            $route = route('employee.leave.history');
        } elseif ($this->notificationType === 'approved' && ($notifiable->user_type === 'department_admin' || $notifiable->user_type === 'hr')) {
            $subject = 'Leave Request Approved';
            $line1 = 'A leave request you were involved in has been approved by the Mayor.';
            $line2 = 'The employee can now proceed with their leave as scheduled.';
            $actionText = 'View Leave Request';
            $route = $notifiable->user_type === 'department_admin' ? route('department.leave.requests') : route('hr.leave.requests');
        } elseif ($this->notificationType === 'disapproved' && $notifiable->user_type === 'employee') {
            $subject = 'Leave Request Disapproved';
            $line1 = 'Your leave request has been disapproved.';
            $line2 = 'Please check the comments provided by the approver.';
            $actionText = 'View Leave Request';
            $route = route('employee.leave.history');
        } elseif ($this->notificationType === 'disapproved' && ($notifiable->user_type === 'department_admin' || $notifiable->user_type === 'hr')) {
            $subject = 'Leave Request Disapproved';
            $line1 = 'A leave request you were involved in has been disapproved by the Mayor.';
            $line2 = 'Please check the comments provided by the approver.';
            $actionText = 'View Leave Request';
            $route = $notifiable->user_type === 'department_admin' ? route('department.leave.requests') : route('hr.leave.requests');
        } else {
            $subject = 'Leave Request Status Updated';
            $line1 = 'The status of your leave request has been updated.';
            $line2 = 'Please check your leave history for details.';
            $actionText = 'View Leave Request';
            $route = route('employee.leave.history');
        }

        return (new MailMessage)
            ->subject($subject)
            ->line($line1)
            ->line('Employee: ' . $employeeName)
            ->line('Leave Type: ' . LeaveRequest::LEAVE_TYPES[$this->leaveRequest->leave_type])
            ->line('Period: ' . $this->leaveRequest->start_date->format('M d, Y') . ' to ' . $this->leaveRequest->end_date->format('M d, Y'))
            ->line('Number of Days: ' . $this->leaveRequest->number_of_days)
            ->when($this->leaveRequest->department_comments || $this->leaveRequest->hr_comments, function ($mail) {
                $comments = $this->leaveRequest->department_comments ?: $this->leaveRequest->hr_comments;
                return $mail->line('Comments: ' . ($comments ?: 'No comments provided'));
            })
            ->action($actionText, $route)
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        // Customize message based on notification type and user role
        if ($this->notificationType === 'new_request' && $notifiable->user_type === 'department_admin') {
            $message = 'New leave request from ' . $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name;
            $status = 'new';
        } elseif ($this->notificationType === 'recommended' && $notifiable->user_type === 'hr') {
            $message = 'Leave request from ' . $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name . ' recommended for approval';
            $status = 'recommended';
        } elseif ($this->notificationType === 'recommended' && $notifiable->user_type === 'employee') {
            $message = 'Your leave request has been recommended by your department head';
            $status = 'recommended';
        } elseif ($this->notificationType === 'disapproved' && $notifiable->user_type === 'employee') {
            $message = 'Your leave request has been disapproved by your department head';
            $status = 'disapproved';
        } elseif ($this->notificationType === 'hr_approved' && $notifiable->user_type === 'mayor') {
            $message = 'Leave request from ' . $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name . ' approved by HR';
            $status = 'hr_approved';
        } elseif ($this->notificationType === 'hr_approved' && $notifiable->user_type === 'employee') {
            $message = 'Your leave request has been approved by HR';
            $status = 'hr_approved';
        } elseif ($this->notificationType === 'approved' && $notifiable->user_type === 'employee') {
            $message = 'Your leave request has been approved';
            $status = 'approved';
        } elseif ($this->notificationType === 'approved' && ($notifiable->user_type === 'department_admin' || $notifiable->user_type === 'hr')) {
            $message = 'A leave request you were involved in has been approved by the Mayor';
            $status = 'approved';
        } elseif ($this->notificationType === 'disapproved' && $notifiable->user_type === 'employee') {
            $message = 'Your leave request has been disapproved';
            $status = 'disapproved';
        } elseif ($this->notificationType === 'disapproved' && ($notifiable->user_type === 'department_admin' || $notifiable->user_type === 'hr')) {
            $message = 'A leave request you were involved in has been disapproved by the Mayor';
            $status = 'disapproved';
        } else {
            $message = 'Leave request status updated';
            $status = 'updated';
        }

        return [
            'leave_request_id' => $this->leaveRequest->id,
            'status' => $status,
            'message' => $message,
            'employee_name' => $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name,
            'leave_type' => LeaveRequest::LEAVE_TYPES[$this->leaveRequest->leave_type],
            'start_date' => $this->leaveRequest->start_date->format('Y-m-d'),
            'end_date' => $this->leaveRequest->end_date->format('Y-m-d'),
            'number_of_days' => $this->leaveRequest->number_of_days,
            'department_comments' => $this->leaveRequest->department_comments ?? '',
            'hr_comments' => $this->leaveRequest->hr_comments ?? '',
        ];
    }
}
