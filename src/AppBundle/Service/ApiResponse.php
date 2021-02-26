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
    public function createApiErrorResponse($errorCode)
    {
        return [
            GeneralConstants::REASON_CODE => ErrorConstants::$errorCodeMap[$errorCode]['code'],
            GeneralConstants::REASON_TEXT => $this->translator
                ->trans(ErrorConstants::$errorCodeMap[$errorCode][GeneralConstants::MESSAGE])
        ];
    }


    /**
     * @param $result
     * @param $authenticateResult
     * @return array
     */
    public function createAuthApiSuccessResponse($result, $authenticateResult)
    {
        return [
            GeneralConstants::REASON_CODE => 0,
            GeneralConstants::REASON_TEXT => $this->translator->trans('api.response.success.message'),
            'LoggedInStaffID' => $result['LoggedInStaffID'],
            GeneralConstants::LOCALEID => $result[GeneralConstants::LOCALEID],
            GeneralConstants::LOGGED_IN_SERVICER_PASSWORD => $result[GeneralConstants::LOGGED_IN_SERVICER_PASSWORD],
            'AccessToken' => $result['AccessToken'],
            'Permissions' => $result['Restrictions'],
            'UserDetails' => array(
                GeneralConstants::CUSTOMER_ID => $authenticateResult[GeneralConstants::MESSAGE][GeneralConstants::CUSTOMER_ID],
                'CustomerName' => $result[GeneralConstants::MESSAGE]['CustomerName'],
                GeneralConstants::LOCALEID => $result[GeneralConstants::LOCALEID],
                GeneralConstants::REGION => $result[GeneralConstants::REGION]
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