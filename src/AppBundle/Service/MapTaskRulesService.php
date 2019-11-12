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
            $integrationID = null;
            $count = null;
            $response = null;

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

                if (array_key_exists('Status', $filters)) {
                    $status = $filters['Status'];

                    // If status is only set to matched
                    if (in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        !in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        if($offset === 1) {
                            $count = $this->entityManager->getRepository('AppBundle:Integrationqbditemstoservices')->CountServicesJoinMatched($customerID,$department, $billable, $createDate);
                            if($count) {
                                $count = (int)$count[0][1];
                            }
                        }
                        $response = $this->entityManager->getRepository('AppBundle:Integrationqbditemstoservices')->ServicesJoinMatched($customerID,$department, $billable, $createDate, $limit, $offset);
                    }

                    // If status is only set to not yet matched
                    if (!in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        if($offset === 1) {
                            $count = $this->entityManager->getRepository('AppBundle:Services')->CountSyncServices($customerID,$department, $billable, $createDate);
                            if($count) {
                                $count = (int)$count[0][1];
                            }
                        }
                        $response = $this->entityManager->getRepository('AppBundle:Services')->SyncServices($customerID,$department, $billable, $createDate, $limit, $offset);
                        for ($i=0;$i<count($response);$i++) {
                            $response[$i]["IntegrationQBDItemID"] = null;
                        }
                    }
                }
            }


            // Default Condition i.e- If status is not set
            if(!$response) {
                if($offset === 1) {
                    $count1 = $this->entityManager->getRepository('AppBundle:Integrationqbditemstoservices')->CountServicesJoinMatched($customerID,$department, $billable, $createDate);
                    if($count1) {
                        $count1 = (int)$count1[0][1];
                    }
                    $count2 = $this->entityManager->getRepository('AppBundle:Services')->CountSyncServices($customerID,$department, $billable, $createDate);

                    if($count2) {
                        $count2 = (int)$count2[0][1];
                    }
                    $count = $count1 + $count2;
                }
                $response2 = null;
                $response = $this->entityManager->getRepository('AppBundle:Integrationqbditemstoservices')->ServicesJoinMatched($customerID,$department, $billable, $createDate, $limit, $offset);
                $countResponse = count($response);
                if($countResponse < $limit) {
                    $limit = $limit-$countResponse;
                    $response2 = $this->entityManager->getRepository('AppBundle:Services')->SyncServices($customerID,$department, $billable, $createDate, $limit, $offset);
                    for($i=0;$i<count($response2);$i++) {
                        $response2[$i]["IntegrationQBDItemID"] = null;
                    }
                }
                $response = array_merge($response,$response2);
            }

            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => array(
                    'Count' => $count,
                    'Details' => $response
                )
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

    /**
     * @param $customerID
     * @return array
     */
    public function FetchItems($customerID)
    {
        try {
            $items = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->QBDItems($customerID);
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => $items
            );
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching items due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}