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

        $today = new \DateTime('now');
        $clockInToday = new \DateTime($clockIn);

        // Set tomorrow as 00:00 midnight (Next Day) in servicer's timezone
        $clockInTomorrow = (new \DateTime($clockInToday->format('Y-m-d'),$timeZone))->modify('+1 day');
        $clockInTomorrow->setTimezone(new \DateTimeZone('UTC'));

        // Check if clock in time falls within one day - range
        if ($today >= $clockInToday && $clockInToday <= $clockInTomorrow) {
            return true;
        }

        return false;
    }
}