<x-layouts.layout>
    <x-slot:title>Test</x-slot:title>
    <x-slot:header>Test</x-slot:header>
    
    @if(true)
        <button onclick="openEditAttendanceModal({{ 1 }}, {{ 2 }}, {{ 3 }}, {{ 4 }})" class="btn btn-sm btn-outline">
            Edit Attendance
        </button>
    @endif
</x-layouts.layout>