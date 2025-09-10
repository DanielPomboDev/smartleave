<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRecord extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'year',
        'vacation_earned',
        'vacation_used',
        'vacation_balance',
        'sick_earned',
        'sick_used',
        'sick_balance',
        'undertime_hours',
        'vacation_entries',
        'sick_entries'
    ];

    protected $casts = [
        'vacation_earned' => 'decimal:3',
        'vacation_used' => 'decimal:3',
        'vacation_balance' => 'decimal:3',
        'sick_earned' => 'decimal:3',
        'sick_used' => 'decimal:3',
        'sick_balance' => 'decimal:3',
        'undertime_hours' => 'decimal:3',
        'vacation_entries' => 'array',
        'sick_entries' => 'array'
    ];

    /**
     * Get the user that owns this leave record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get formatted sick entries.
     */
    public function getFormattedSickEntriesAttribute()
    {
        if (!$this->sick_entries) {
            return [];
        }
        
        // Format sick entries to ensure they have a 'days' value
        $formattedEntries = [];
        foreach ($this->sick_entries as $entry) {
            $formattedEntry = $entry;
            // If 'days' is not set but 'hours' is, calculate days (assuming 8-hour workday)
            if (!isset($entry['days']) && isset($entry['hours'])) {
                $formattedEntry['days'] = $entry['hours'] / 8;
            }
            // If neither 'days' nor 'hours' is set, default to 0
            if (!isset($formattedEntry['days'])) {
                $formattedEntry['days'] = 0;
            }
            $formattedEntries[] = $formattedEntry;
        }
        
        return $formattedEntries;
    }

    /**
     * Get formatted vacation entries.
     */
    public function getFormattedVacationEntriesAttribute()
    {
        if (!$this->vacation_entries) {
            return [];
        }
        
        // Format vacation entries to ensure they have a 'days' value
        $formattedEntries = [];
        foreach ($this->vacation_entries as $entry) {
            $formattedEntry = $entry;
            // If 'days' is not set but we have start and end dates, calculate days
            if (!isset($entry['days']) && isset($entry['start_date']) && isset($entry['end_date'])) {
                $startDate = new \DateTime($entry['start_date']);
                $endDate = new \DateTime($entry['end_date']);
                $interval = $startDate->diff($endDate);
                $formattedEntry['days'] = $interval->days + 1; // +1 to include both start and end dates
            }
            // If 'days' is still not set, default to 0
            if (!isset($formattedEntry['days'])) {
                $formattedEntry['days'] = 0;
            }
            $formattedEntries[] = $formattedEntry;
        }
        
        return $formattedEntries;
    }

    /**
     * Get formatted undertime display.
     */
    public function getFormattedUndertimeAttribute()
    {
        if ($this->undertime_hours <= 0) {
            return '0.000';
        }
        
        // Return the decimal value formatted to 3 decimal places
        return number_format($this->undertime_hours, 3, '.', '');
    }

    /**
     * Get the month name for this record.
     */
    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
        
        return $months[$this->month] ?? 'Unknown';
    }

    /**
     * Get the full month and year name.
     */
    public function getMonthYearAttribute()
    {
        return $this->month_name . ' ' . $this->year;
    }

    /**
     * Scope a query to only include records for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include records for a specific month/year.
     */
    public function scopeForMonth($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    /**
     * Scope a query to only include records for a specific year.
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }
}