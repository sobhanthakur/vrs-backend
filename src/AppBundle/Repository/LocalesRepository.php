<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 29/9/20
 * Time: 11:39 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class LocalesRepository extends EntityRepository
{
    public function GetLocales()
    {
        return $this->createQueryBuilder('l')
            ->select('l.localeid AS LocaleID')
            ->addSelect('l.locale AS Locale')
            ->addSelect('l.localereadable AS LocaleReadable')
            ->getQuery()
            ->execute();
    }
}