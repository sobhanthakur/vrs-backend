<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 29/5/20
 * Time: 3:25 PM
 */

namespace AppBundle\CustomClasses;


/**
 * Class TimeZoneConverter
 * @package AppBundle\CustomClasses
 */
class TimeZoneConverter
{
    /**
     * @param $clockIn
     * @param $region
     * @return bool
     */
    final public function RangeCalculation($clockIn, $timeZone)
    {
        $today = (new \DateTime('now',$timeZone))->setTime(0,0,0)->setTimezone(new \DateTimeZone('UTC'));
        $todayEOD = (new \DateTime('now',$timeZone))->modify('+1 day')->setTime(0,0,0)->setTimezone(new \DateTimeZone('UTC'));
        $clockIn = new \DateTime($clockIn);

        // Check if clock in time falls within one day - range
        if ($clockIn >= $today && $clockIn <= $todayEOD) {
            return true;
        }

        return false;
    }
}