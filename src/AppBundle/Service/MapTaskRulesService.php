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
use AppBundle\Entity\Integrationqbditemstoservices;
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
            $flag = null;

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
                if (array_key_exists(GeneralConstants::BILLABLE, $filters)) {
                    $billable = $filters[GeneralConstants::BILLABLE];
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
                        for($i=0;$i<count($response);$i++) {
                            $response[$i]['LaborOrMaterials'] = $response[$i]['LaborOrMaterials'] === true ? 1: 0;
                        }
                        $flag = 1;
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
                            $response[$i]["LaborOrMaterials"] = null;
                        }
                        $flag = 1;
                    }
                }
            }


            // Default Condition i.e- If status is not set
            if(!$flag) {
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
                for($i=0;$i<count($response);$i++) {
                    $response[$i]['LaborOrMaterials'] = $response[$i]['LaborOrMaterials'] === true ? 1: 0;
                }
                $countResponse = count($response);
                if($countResponse < $limit) {
                    $limit = $limit-$countResponse;
                    $response2 = $this->entityManager->getRepository('AppBundle:Services')->SyncServices($customerID,$department, $billable, $createDate, $limit, $offset);
                    for($i=0;$i<count($response2);$i++) {
                        $response2[$i]["IntegrationQBDItemID"] = null;
                        $response2[$i]["LaborOrMaterials"] = null;
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

    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function MapTaskRulesToItems($customerID, $content)
    {
        try {
            if(!array_key_exists('IntegrationID',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }

            $integrationID = $content['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncBilling is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncBillingEnabled($integrationID,$customerID);
            if(empty($integrationToCustomers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if(!array_key_exists('Data',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_DATA);
            }

            $data = $content['Data'];

            // Traverse data to create/update mappings
            for ($i = 0; $i < count($data); $i++) {
                $itemsToTaskrules = $this->entityManager->getRepository('AppBundle:Integrationqbditemstoservices')->findOneBy(
                    array(
                        'serviceid' => $data[$i][GeneralConstants::TASKRULEID],
                        'laborormaterials' => $data[$i][GeneralConstants::BILLTYPE]
                    )
                );

                $integrationQBDItems = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->findOneBy(array(
                        'integrationqbditemid' => $data[$i][GeneralConstants::INTEGRATION_QBD_ITEM_ID]
                    )
                );

                // Check if the integration QBD Customer is present. Or if the customer ID is valid or not
                if(!$integrationQBDItems ||
                    ($integrationQBDItems !== null?($integrationQBDItems->getCustomerid()->getCustomerid() !== $customerID):null)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATIONQBDITEMID);
                }

                // Integration QBD Items To TaskRules exist, then simply update the record with the new IntegrationQBDCustomerID
                if (!$itemsToTaskrules) {
                    // Create New Record
                    $taskRule = $this->entityManager->getRepository('AppBundle:Services')->findOneBy(array(
                            'serviceid' => $data[$i][GeneralConstants::TASKRULEID]
                        )
                    );
                    if(!$taskRule ||
                        ($taskRule !== null?($taskRule->getCustomerid()->getCustomerid() !== $customerID):null)
                    ) {
                        throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKRULE_ID);
                    }

                    $itemsToTaskrules = new Integrationqbditemstoservices();

                    $itemsToTaskrules->setIntegrationqbditemid($integrationQBDItems);
                    $itemsToTaskrules->setServiceid($taskRule);
                    $itemsToTaskrules->setLaborormaterials(
                        $data[$i][GeneralConstants::BILLTYPE] === 0 ? false:true
                    );

                    $this->entityManager->persist($itemsToTaskrules);
                } else {
                    // Update the record
                    $itemsToTaskrules->setIntegrationqbditemid($integrationQBDItems);
                    $this->entityManager->persist($itemsToTaskrules);
                }
            }
            $this->entityManager->flush();

            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message')
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Saving mapped information for TaskRules due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}