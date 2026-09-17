<?php

return [

    // Fixed company work hours used to calculate lateness and overtime.
    // Format: 24-hour "H:i" e.g. "09:00"
    'work_start' => env('COMPANY_WORK_START', '09:00'),
    'work_end' => env('COMPANY_WORK_END', '18:00'),

    // Minutes of grace period after work_start before a clock-in counts as late.
    'late_grace_minutes' => (int) env('COMPANY_LATE_GRACE_MINUTES', 10),

];
