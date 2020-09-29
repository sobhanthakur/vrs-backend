<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 23/9/20
 * Time: 2:40 PM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class TranslationsRepository extends EntityRepository
{

    public function GetTranslations($localeID)
    {
        return $this->createQueryBuilder('t')
            ->select('t.translationid AS TranslationID,t.translatedtext AS TranslatedText,e.englishtext AS EnglishText,l.locale AS Locale')
            ->leftJoin('t.LocaleID','l')
            ->leftJoin('t.translationTextID','e')
            ->where('l.localeid='.$localeID)
            ->getQuery()
            ->execute();
    }
}