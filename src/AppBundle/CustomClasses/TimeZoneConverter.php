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
        $today = (new \DateTime('now'));
        $clockInToday = new \DateTime($clockIn);
        $clockInTomorrow = (new \DateTime($clockInToday->format('Y-m-d'),$timeZone))->modify('+1 day');
        $clockInTomorrow->setTimezone(new \DateTimeZone('UTC'));

        if ($today >= $clockInToday && $today <= $clockInTomorrow) {
            return true;
        }
        return false;
    }
}