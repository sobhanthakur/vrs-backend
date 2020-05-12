<?php
/**
 * Created by PhpStorm.
 * User: Sobhan Thakur
 * Date: 7/10/19
 * Time: 7:05 PM
 */

namespace AppBundle\CustomClasses;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class DateTime
 * @package AppBundle\CustomClasses
 */
class DateTime extends DateTimeType
{
    /**
     * @var string
     */
    private $dateTimeFormatString = 'Y-m-d H:i:s.000';

    /**
     * @param \DateTime $value
     * @param AbstractPlatform $platform
     * @return mixed|null|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null)
            ? $value->format($this->dateTimeFormatString) : null;
    }
}