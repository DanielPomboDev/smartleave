<x-layouts.layout>
    <x-slot:title>Department Admin Settings</x-slot:title>
    <x-slot:header>Account Settings</x-slot:header>

    <div class="card bg-white shadow-md">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-6">
                <i class="fi-rr-settings text-blue-500 mr-2"></i>
                Account Settings
            </h2>

            <form class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email Address</span>
                    </label>
                    <input type="email" class="input input-bordered" value="{{ Auth::user()->email }}" disabled />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Notification Preferences</span>
                    </label>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" class="checkbox checkbox-primary" />
                            <span>Email Notifications</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" class="checkbox checkbox-primary" />
                            <span>SMS Notifications</span>
                        </div>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Time Zone</span>
                    </label>
                    <select class="select select-bordered">
                        <option>Philippines (UTC+8)</option>
                        <option>UTC</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
</x-layouts.layout>
