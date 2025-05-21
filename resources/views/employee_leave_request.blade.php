<x-layouts.layout>
    <x-slot:title>Request Leave</x-slot:title>
    <x-slot:header>Request Leave</x-slot:header>
    
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-6">
                <i class="fi-rr-calendar text-blue-500 mr-2"></i>
                Request New Leave
            </h2>
            
            <form action="{{ route('leave.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Leave Type -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Leave Type</span>
                    </label>
                    <select name="leave_type" class="select select-bordered border-gray-300 focus:border-blue-500 w-full" required>
                        <option value="">Select Leave Type</option>
                        <option value="vacation">Vacation Leave</option>
                        <option value="sick">Sick Leave</option>
                        <option value="emergency">Emergency Leave</option>
                    </select>
                </div>
                
                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Start Date</span>
                        </label>
                        <input type="date" name="start_date" class="input input-bordered border-gray-300 focus:border-blue-500" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">End Date</span>
                        </label>
                        <input type="date" name="end_date" class="input input-bordered border-gray-300 focus:border-blue-500" required>
                    </div>
                </div>
                
                <!-- Reason -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Reason for Leave</span>
                    </label>
                    <textarea name="reason" class="textarea textarea-bordered border-gray-300 focus:border-blue-500" rows="4" placeholder="Please provide a brief description of why you need to take leave..." required></textarea>
                </div>
                
                <!-- Attachments -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Supporting Documents (optional)</span>
                    </label>
                    <input type="file" name="attachments[]" class="file-input file-input-bordered w-full" multiple>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fi-rr-paper-plane mr-2"></i>
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.layout>
