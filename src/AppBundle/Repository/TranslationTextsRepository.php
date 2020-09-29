<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 29/9/20
 * Time: 11:26 AM
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class TranslationTextsRepository extends EntityRepository
{
    public function EnglishTexts()
    {
        return $this->createQueryBuilder('e')
            ->select('e.translationtextid AS TranslationTextID,e.englishtext AS EnglishText')
            ->getQuery()
            ->execute();
    }

    public function GetMappedTranslations($id)
    {
        return $this->createQueryBuilder('e')
            ->select('e.englishtext AS EnglishText,e.translationtextid AS TranslationTextID,t2.translationid AS TranslationID,t2.translatedtext AS TranslatedText')
            ->leftJoin('AppBundle:Translations', 't2', Expr\Join::WITH, 't2.translationTextID=e.translationtextid')
            ->where('t2.LocaleID='.$id.' OR t2.LocaleID IS NULL')
            ->getQuery()
            ->execute();
    }

}