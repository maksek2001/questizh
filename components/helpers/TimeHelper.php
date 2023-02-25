<?php

namespace app\helpers;

class TimeHelper
{
    public static function secondsToTimeString(int $seconds): string
    {
        $timeString = '';
        
        if ($seconds > 0) {
            $hours = floor($seconds / 3600);
            $minutes = floor($seconds / 60 - $hours * 60);
            $seconds = $seconds - $hours * 3600 - $minutes * 60;


            if ($hours > 0)
                $timeString .= $hours . ' ч. ';

            if ($minutes > 0)
                $timeString .= $minutes . ' мин. ';

            if ($seconds > 0)
                $timeString .= $seconds . ' сек. ';
        }
        
        return $timeString;
    }
}
