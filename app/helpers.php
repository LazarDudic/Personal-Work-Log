<?php

if (! function_exists('differential_day_checked')) {
    function differential_day_checked($day, $differentialDays)
    {
        if (! $differentialDays) { return null; }

        return in_array($day, $differentialDays) ? 'checked' : null;
    }
}

if (! function_exists('convert_minutes_to_hours')) {
    function convert_minutes_to_hours($minutes)
    {
        if (! $minutes) { return '-'; }

        $hours = \Carbon\Carbon::parse($minutes * 60)->format('H:i');

        return $hours == '00:00' ? '24:00' : $hours;
    }
}
