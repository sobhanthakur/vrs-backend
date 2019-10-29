<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 29/10/19
 * Time: 11:18 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class MapTaskRulesService
 * @package AppBundle\Service
 */
class MapTaskRulesService extends BaseService
{
    /**
     * @param $customerID
     * @param $data
     * @return array
     */
    public function MapTaskRules($customerID, $data)
    {
        try {
            // Initialize variables
            $filters = null;
            $status = null;
            $department = null;
            $billable = null;
            $createDate = null;
            $limit = 10;
            $offset = 1;
            $itemsToServices = null;
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
                    $itemsToServices = $this->entityManager->getRepository('AppBundle:Integrationqbditemstoservices')->ServicesJoinMatched($customerID);

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
                if (array_key_exists('Department', $filters)) {
                    $department = $filters['Department'];
                }
                if (array_key_exists('Billable', $filters)) {
                    $billable = $filters['Billable'];
                }
                if (array_key_exists('CreateDate', $filters)) {
                    $createDate = $filters['CreateDate'];
                }
                if (array_key_exists('Pagination', $data)) {
                    $limit = $data['Pagination']['Limit'];
                    $offset = $data['Pagination']['Offset'];
                }
            }

            $taskRules = $this->entityManager->getRepository('AppBundle:Services')->SyncServices($itemsToServices, $department, $billable, $createDate, $limit, $offset, $customerID, $matchStatus);
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => $taskRules
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed mapping TaskRules due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}