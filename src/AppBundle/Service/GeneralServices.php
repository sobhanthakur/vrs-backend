<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 14/1/20
 * Time: 12:43 PM
 */

namespace AppBundle\Service;


class GeneralServices extends BaseService
{
    /**
     * Function to validate query paramter request value
     *
     * @param $queryParameter
     *
     * @return boolean
     */
    public function validationCheck($queryParameter)
    {
        foreach ($queryParameter as $paramKey => $paramValue) {
            switch ($paramKey) {
                case 'active':
                case 'fields':
                case 'sort':
                    if (is_string($paramValue) === false) {
                        return false;
                    }
                    break;

                case 'ownerid':
                case 'regionid':
                case 'limit':
                case 'startingafter':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;
                default:
                    break;
            }
        }
        return true;
    }
}