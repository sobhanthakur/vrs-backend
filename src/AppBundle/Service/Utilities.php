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
    public function UtcToLocalToUtcConversion($region,$dateTime='now')
    {
        $dateTime = new \DateTime($dateTime);
        $dateTime->setTimezone(new \DateTimeZone($region));
        return (new \DateTime($dateTime->format('Y-m-d H:i:s'),new \DateTimeZone('UTC')));
    }

}