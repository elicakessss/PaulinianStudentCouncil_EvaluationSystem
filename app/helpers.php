<?php

if (!function_exists('activity')) {
    /**
     * Helper function for activity logging
     */
    function activity($logName = null)
    {
        return Spatie\Activitylog\Facades\Activity::inLog($logName);
    }
}
