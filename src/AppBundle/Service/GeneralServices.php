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
                    if (is_string($paramValue) === false) {
                        return false;
                    }
                    break;

                case 'ownerid':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;

                case 'regionid':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;

                case 'fields':
                    if ((is_string($paramValue)) === false) {
                        return false;
                    }
                    break;
                case 'sort':
                    if ((is_string($paramValue)) === false) {
                        return false;
                    }
                    break;

                case 'limit':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;
                case 'startingafter':
                    if ((is_numeric($paramValue)) === false) {
                        return false;
                    }
                    break;
            }

        }

        return true;
    }

}