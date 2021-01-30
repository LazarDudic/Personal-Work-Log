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

        $hours = \Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::now()->addMinutes($minutes));
        $hours = strval($hours);
        $minutes = $minutes % 60;

        if (strlen($hours) < 2) {
            $hours = '0'.$hours;
        }

        if (strlen($minutes) < 2) {
            $minutes = '0'.$minutes;
        }

        return $hours. ':' . $minutes;
    }
}
