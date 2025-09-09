<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestWorkflowNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $leaveRequest;
    protected $notificationType;
    protected $additionalData;

    public function __construct(LeaveRequest $leaveRequest, $notificationType = 'new_request', $additionalData = [])
    {
        $this->leaveRequest = $leaveRequest;
        $this->notificationType = $notificationType;
        $this->additionalData = $additionalData;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $employeeName = $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name;
        
        switch ($this->notificationType) {
            case 'new_request':
                $subject = 'New Leave Request Submitted';
                $line1 = 'A new leave request has been submitted by ' . $employeeName . '.';
                $line2 = 'Please review this request in your department dashboard.';
                $actionText = 'Review Leave Request';
                $route = route('department.leave.requests');
                break;
                
            case 'recommended':
                $subject = 'Leave Request Recommended for Approval';
                $line1 = 'A leave request from ' . $employeeName . ' has been recommended by their department head.';
                $line2 = 'Please review this request for HR approval.';
                $actionText = 'Review Leave Request';
                $route = route('hr.leave.requests');
                break;
                
            case 'hr_approved':
                $subject = 'Leave Request Approved by HR';
                $line1 = 'A leave request from ' . $employeeName . ' has been approved by HR.';
                $line2 = 'Please review this request for final approval.';
                $actionText = 'Review Leave Request';
                $route = route('mayor.leave.requests');
                break;
                
            case 'hr_disapproved':
                $subject = 'Leave Request Disapproved by HR';
                $line1 = 'Your leave request has been disapproved by HR.';
                $line2 = 'Please check the comments provided by HR.';
                $actionText = 'View Leave Request';
                $route = route('employee.leave.history');
                break;
                
            case 'mayor_approved':
                $subject = 'Leave Request Final Approved';
                $line1 = 'Your leave request has been final approved by the Mayor.';
                $line2 = 'You can now proceed with your leave as scheduled.';
                $actionText = 'View Leave Request';
                $route = route('employee.leave.history');
                break;
                
            case 'mayor_disapproved':
                $subject = 'Leave Request Final Disapproved';
                $line1 = 'Your leave request has been final disapproved by the Mayor.';
                $line2 = 'Please check the comments provided by the Mayor.';
                $actionText = 'View Leave Request';
                $route = route('employee.leave.history');
                break;
                
            case 'department_disapproved':
                $subject = 'Leave Request Disapproved by Department';
                $line1 = 'Your leave request has been disapproved by your department head.';
                $line2 = 'Please check the comments provided by your department head.';
                $actionText = 'View Leave Request';
                $route = route('employee.leave.history');
                break;
                
            default:
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
            ->when(!empty($this->additionalData['comments']), function ($mail) {
                return $mail->line('Comments: ' . ($this->additionalData['comments'] ?? 'No comments provided'));
            })
            ->action($actionText, $route)
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        switch ($this->notificationType) {
            case 'new_request':
                $message = 'New leave request from ' . $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name;
                $status = 'new';
                break;
                
            case 'recommended':
                $message = 'Leave request from ' . $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name . ' recommended for approval';
                $status = 'recommended';
                break;
                
            case 'hr_approved':
                $message = 'Leave request from ' . $this->leaveRequest->user->first_name . ' ' . $this->leaveRequest->user->last_name . ' approved by HR';
                $status = 'hr_approved';
                break;
                
            case 'hr_disapproved':
                $message = 'Your leave request has been disapproved by HR';
                $status = 'hr_disapproved';
                break;
                
            case 'mayor_approved':
                $message = 'Your leave request has been final approved by the Mayor';
                $status = 'mayor_approved';
                break;
                
            case 'mayor_disapproved':
                $message = 'Your leave request has been final disapproved by the Mayor';
                $status = 'mayor_disapproved';
                break;
                
            case 'department_disapproved':
                $message = 'Your leave request has been disapproved by your department head';
                $status = 'department_disapproved';
                break;
                
            default:
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
            'comments' => $this->additionalData['comments'] ?? '',
        ];
    }
}