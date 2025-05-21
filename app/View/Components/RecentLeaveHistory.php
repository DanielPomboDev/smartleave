<?php

namespace App\View\Components;

use App\Models\LeaveRequest;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class RecentLeaveHistory extends Component
{
    /**
     * The leave requests to display.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $leaveRequests;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Get the latest 5 leave requests for the authenticated user
        $this->leaveRequests = LeaveRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.recent-leave-history', [
            'leaveRequests' => $this->leaveRequests
        ]);
    }
}
