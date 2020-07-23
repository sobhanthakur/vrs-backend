<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/7/20
 * Time: 11:03 AM
 */

namespace AppBundle\Service;


/**
 * Class Utilities
 * @package AppBundle\Service
 */
class Utilities
{
    /**
     * @param \DateTime $dateTime
     * @param $region
     * @return \DateTime
     */
    public function UtcToLocalConversion($region,$dateTime='now')
    {
        $dateTime = new \DateTime($dateTime);
        $dateTime->setTimezone(new \DateTimeZone($region));
        return $dateTime;
    }

}