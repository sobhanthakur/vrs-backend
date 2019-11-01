<?php
/**
 *  Service Class for Creating API Request Response.
 *
 * @category Service
 * @author Sobhan Thakur
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;


/**
 * Class ApiResponse
 * @package AppBundle\Service
 */
class ApiResponse extends BaseService
{
    /**
     *  Function to create API Error Response.
     *
     * @param string $errorCode
     *
     * @return array
     */
    public function createApiErrorResponse($errorCode, $status)
    {
        return [
            GeneralConstants::REASON_CODE => ErrorConstants::$errorCodeMap[$errorCode]['code'],
            GeneralConstants::REASON_TEXT => $this->translator
                ->trans(ErrorConstants::$errorCodeMap[$errorCode][GeneralConstants::MESSAGE])
        ];
    }


    /**
     * Function to create final Success Admin API response.
     * @param $loggedInStaffID
     * @param $restrictions
     * @return array
     */
    public function createAuthApiSuccessResponse($result, $authenticateResult)
    {
        return [
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans('api.response.success.message'),
            'LoggedInStaffID' => $result['LoggedInStaffID'],
            'AccessToken' => $result['AccessToken'],
            'Permissions' => $result['Restrictions'],
            'UserDetails' => array(
                'CustomerID' => $authenticateResult[GeneralConstants::MESSAGE]['CustomerID'],
                'CustomerName' => $authenticateResult[GeneralConstants::MESSAGE]['CustomerName']
            )
        ];
    }

    /**
     * @return array
     */
    public function GenericSuccessResponse()
    {
        return array(
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans('api.response.success.message')
        );
    }
}