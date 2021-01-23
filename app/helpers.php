<?php

if (! function_exists('differential_day_checked')) {
    function differential_day_checked($day, $differentialDays)
    {
        if (! $differentialDays) { return false; }

        $differentialDays = json_decode($differentialDays);

        return in_array($day, $differentialDays) ? 'checked' : false;
    }
}
