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
        $response = [
            'Response' => [
                'ReasonCode' => 1,
                'ReasonText' => $this->translator->trans('api.response.failure.message'),
                'Error' => [
                    'Code' => ErrorConstants::$errorCodeMap[$errorCode]['code'],
                    'Text' => $this->translator
                        ->trans(ErrorConstants::$errorCodeMap[$errorCode]['message'])
                ],
            ]
        ];
        return $response;
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
            'ReasonCode' => 0,
            'ReasonText' => $this->translator->trans('api.response.success.message'),
            'LoggedInStaffID' => $result['LoggedInStaffID'],
            'AccessToken' => $result['AccessToken'],
            'Permissions' => $result['Restrictions'],
            'UserDetails' => array(
                'CustomerID' => $authenticateResult['message']['CustomerID'],
                'CustomerName' => $authenticateResult['message']['CustomerName']
            )
        ];
    }
}