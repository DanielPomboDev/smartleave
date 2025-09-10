# Automated Leave Credit Calculation

## How It Works

The system automatically calculates and awards leave credits at the beginning of each month based on employee attendance from the previous month.

## Process

1. **First Day of Each Month**: The `leave:calculate-monthly-credits` command runs automatically at 1:00 AM
2. **LWOP Calculation**: The system reviews all approved leave requests from the previous month to calculate LWOP days
3. **Credit Award**: Based on attendance, employees receive:
   - Prorated vacation credits (based on LWOP days)
   - Standard 1.250 sick credits (not affected by LWOP)
4. **Balance Update**: Current leave balances are updated accordingly

## Manual Execution

To manually run the calculation for a specific month:
```bash
php artisan leave:calculate-monthly-credits --month=9 --year=2025
```

## Scheduling

The command is scheduled to run automatically on the first day of each month at 1:00 AM via the Console Kernel.

## Key Points

- Leave credits are only awarded at month-end based on actual attendance
- Individual leave approvals do not immediately award credits
- The system prevents premature credit accumulation
- All calculations follow the official leave credit tables