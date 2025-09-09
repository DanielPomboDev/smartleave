<x-layouts.layout>
    <x-slot:title>Edit Leave Record</x-slot:title>
    <x-slot:header>Edit Leave Record</x-slot:header>

    <div class="card bg-white shadow-md">
        <div class="card-body">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-6">
                <i class="fi-rr-edit text-blue-500 mr-2"></i>
                Edit Leave Record
            </h2>

            @if(session('error'))
                <div class="alert alert-error mb-6">
                    <div class="flex items-center">
                        <i class="fi-rr-exclamation text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-bold">Error</h3>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('hr.leave-records.update', $record->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Employee Information -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700">Employee</span>
                        </label>
                        <div class="bg-gray-100 p-3 rounded-lg">
                            <span class="font-medium">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                            <span class="text-gray-500">({{ $employee->user_id }})</span>
                        </div>
                        <input type="hidden" name="user_id" value="{{ $employee->user_id }}">
                    </div>

                    <!-- Month and Year -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Month</span>
                            </label>
                            <select name="month" class="select select-bordered w-full @error('month') select-error @enderror" required>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('month', $record->month) == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Year</span>
                            </label>
                            <input type="number" name="year" min="2020" max="2030" 
                                   class="input input-bordered w-full @error('year') input-error @enderror" 
                                   value="{{ old('year', $record->year) }}" required>
                            @error('year')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Vacation Leave Section -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h3 class="font-bold text-lg text-blue-800 mb-4">Vacation Leave</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Earned <span class="text-red-500">*</span></span>
                            </label>
                            <input type="number" step="0.001" name="vacation_earned" 
                                   class="input input-bordered w-full @error('vacation_earned') input-error @enderror" 
                                   value="{{ old('vacation_earned', $record->vacation_earned) }}" required>
                            @error('vacation_earned')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Used <span class="text-red-500">*</span></span>
                            </label>
                            <input type="number" step="0.001" name="vacation_used" 
                                   class="input input-bordered w-full @error('vacation_used') input-error @enderror" 
                                   value="{{ old('vacation_used', $record->vacation_used) }}" required>
                            @error('vacation_used')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Balance</span>
                            </label>
                            <input type="number" step="0.001" name="vacation_balance" 
                                   class="input input-bordered w-full" 
                                   value="{{ old('vacation_earned', $record->vacation_earned) - old('vacation_used', $record->vacation_used) }}" 
                                   readonly>
                        </div>
                    </div>
                </div>

                <!-- Sick Leave Section -->
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <h3 class="font-bold text-lg text-green-800 mb-4">Sick Leave</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Earned <span class="text-red-500">*</span></span>
                            </label>
                            <input type="number" step="0.001" name="sick_earned" 
                                   class="input input-bordered w-full @error('sick_earned') input-error @enderror" 
                                   value="{{ old('sick_earned', $record->sick_earned) }}" required>
                            @error('sick_earned')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Used <span class="text-red-500">*</span></span>
                            </label>
                            <input type="number" step="0.001" name="sick_used" 
                                   class="input input-bordered w-full @error('sick_used') input-error @enderror" 
                                   value="{{ old('sick_used', $record->sick_used) }}" required>
                            @error('sick_used')
                                <label class="label">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium text-gray-700">Balance</span>
                            </label>
                            <input type="number" step="0.001" name="sick_balance" 
                                   class="input input-bordered w-full" 
                                   value="{{ old('sick_earned', $record->sick_earned) - old('sick_used', $record->sick_used) }}" 
                                   readonly>
                        </div>
                    </div>
                </div>

                <!-- Undertime Section -->
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <h3 class="font-bold text-lg text-yellow-800 mb-4">Undertime</h3>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700">Hours <span class="text-red-500">*</span></span>
                        </label>
                        <input type="number" step="0.01" name="undertime_hours" 
                               class="input input-bordered w-full @error('undertime_hours') input-error @enderror" 
                               value="{{ old('undertime_hours', $record->undertime_hours) }}" required>
                        @error('undertime_hours')
                            <label class="label">
                                <span class="label-text-alt text-red-500">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('leave.record.show', $record->user_id) }}" class="btn btn-outline">
                        <i class="fi-rr-arrow-left mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fi-rr-check mr-2"></i>
                        Update Leave Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Calculate balances when earned or used values change
        document.addEventListener('DOMContentLoaded', function() {
            // Vacation leave balance calculation
            const vacationEarned = document.querySelector('input[name="vacation_earned"]');
            const vacationUsed = document.querySelector('input[name="vacation_used"]');
            const vacationBalance = document.querySelector('input[name="vacation_balance"]');
            
            // Sick leave balance calculation
            const sickEarned = document.querySelector('input[name="sick_earned"]');
            const sickUsed = document.querySelector('input[name="sick_used"]');
            const sickBalance = document.querySelector('input[name="sick_balance"]');
            
            if (vacationEarned && vacationUsed && vacationBalance) {
                function calculateVacationBalance() {
                    const earned = parseFloat(vacationEarned.value) || 0;
                    const used = parseFloat(vacationUsed.value) || 0;
                    vacationBalance.value = (earned - used).toFixed(3);
                }
                
                vacationEarned.addEventListener('input', calculateVacationBalance);
                vacationUsed.addEventListener('input', calculateVacationBalance);
            }
            
            if (sickEarned && sickUsed && sickBalance) {
                function calculateSickBalance() {
                    const earned = parseFloat(sickEarned.value) || 0;
                    const used = parseFloat(sickUsed.value) || 0;
                    sickBalance.value = (earned - used).toFixed(3);
                }
                
                sickEarned.addEventListener('input', calculateSickBalance);
                sickUsed.addEventListener('input', calculateSickBalance);
            }
        });
    </script>
</x-layouts.layout>