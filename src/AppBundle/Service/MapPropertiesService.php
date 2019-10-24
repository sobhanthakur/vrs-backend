<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 16/10/19
 * Time: 2:35 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class MapPropertiesService
 * @package AppBundle\Service
 */
class MapPropertiesService extends BaseService
{
    /**
     * @param $customerID
     * @param $data
     * @return array
     */
    public function MapProperties($customerID, $data)
    {
        try {
            // Initialize variables
            $filters = null;
            $status = null;
            $propertyTags = null;
            $region = null;
            $owner = null;
            $createDate = null;
            $limit = 10;
            $offset = 1;
            $customersToProperties = null;
            $matchStatus = 2;
            $integrationID = null;

            if(!array_key_exists('IntegrationID',$data)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $data['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncBilling is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncBillingEnabled($integrationID,$customerID);
            if(empty($integrationToCustomers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if (!empty($data)) {
                $filters = array_key_exists('Filters', $data) ? $data['Filters'] : [];
                if (array_key_exists('Status', $filters)) {
                    $status = $filters['Status'];
                    $customersToProperties = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomerstoproperties')->PropertiesJoinMatched($customerID);

                    // If status is only set to matched
                    if (in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        !in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        $matchStatus = 1;
                    }

                    // If status is only set to not yet matched
                    if (!in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        $matchStatus = 0;
                    }
                }
                if (array_key_exists('PropertyTag', $filters)) {
                    $propertyTags = $this->entityManager->getRepository('AppBundle:Propertiestopropertygroups')->PropertiestoPropertyGroupsJoinMatched($filters['PropertyTag']);
                }
                if (array_key_exists('Region', $filters)) {
                    $region = $filters['Region'];
                }
                if (array_key_exists('Owner', $filters)) {
                    $owner = $filters['Owner'];
                }
                if (array_key_exists('CreateDate', $filters)) {
                    $createDate = $filters['CreateDate'];
                }
                if (array_key_exists('Pagination', $data)) {
                    $limit = $data['Pagination']['Limit'];
                    $offset = $data['Pagination']['Offset'];
                }
            }

            $properties = $this->entityManager->getRepository('AppBundle:Properties')->SyncProperties($customersToProperties, $propertyTags, $region, $owner, $createDate, $limit, $offset, $customerID, $matchStatus);
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => $properties
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed mapping properties due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $customerID
     * @return array
     */
    public function FetchCustomers($customerID)
    {
        try {
            $customers = $this->entityManager->getRepository('AppBundle:Integrationqbdcustomers')->QBDCustomers($customerID);
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => $customers
            );
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching customers due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}