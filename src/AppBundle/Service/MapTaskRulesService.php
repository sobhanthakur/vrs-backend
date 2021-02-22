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
    private $serviceid = 'serviceid';

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
            $limit = 10;
            $offset = 1;
            $integrationID = null;
            $count = null;
            $response = null;
            $matched = 3;

            if (!array_key_exists(GeneralConstants::INTEGRATION_ID, $data)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $data[GeneralConstants::INTEGRATION_ID];

            //Check if the customer has enabled the integration or not and QBDSyncBilling is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncBillingEnabled($integrationID, $customerID);
            if (empty($integrationToCustomers)) {
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
                if (array_key_exists(GeneralConstants::PAGINATION, $data)) {
                    $limit = $data[GeneralConstants::PAGINATION]['Limit'];
                    $offset = $data[GeneralConstants::PAGINATION]['Offset'];
                }

                if (array_key_exists('Status', $filters)) {
                    $status = $filters['Status'];

                    // If status is only set to matched
                    if (in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        !in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        $matched = 1;
                    }

                    // If status is only set to not yet matched
                    if (!in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        $matched = 2;
                    }
                }
            }

            // Show only labor items
            if ((int)$integrationToCustomers[0]['qbdsyncbilling'] !== 1 && (int)$integrationToCustomers[0]['timetrackingtype'] === 1) {
                $result = $this->entityManager->getRepository('AppBundle:Services')->SyncServices($customerID, $department, $billable, $matched);
                if ($offset === 1) {
                    $sql = $this->getEntityManager()->getConnection()->prepare($result[GeneralConstants::RESULT2]);
                    $sql->execute();
                    $count = count($sql->fetchAll());
                }
                $response = $this->getEntityManager()->getConnection()->prepare($result[GeneralConstants::RESULT2] . ' ORDER BY s0_.ServiceName OFFSET ' . (($offset - 1) * $limit) . ' ROWS FETCH NEXT ' . $limit . ' ROWS ONLY');
            } else {
                // Show both labor and material items
                $result = $this->entityManager->getRepository('AppBundle:Services')->SyncServices($customerID, $department, $billable, $matched);
                if ($offset === 1) {
                    $sql = $this->getEntityManager()->getConnection()->prepare($result['Result1'] . ' UNION ' . $result[GeneralConstants::RESULT2]);
                    $sql->execute();
                    $count = count($sql->fetchAll());
                }
                $response = $this->getEntityManager()->getConnection()->prepare($result['Result1'] . ' UNION ' . $result[GeneralConstants::RESULT2] . ' ORDER BY s0_.ServiceName OFFSET ' . (($offset - 1) * $limit) . ' ROWS FETCH NEXT ' . $limit . ' ROWS ONLY');
            }

            // Execute the result
            $response->execute();
            $response = $response->fetchAll();
            $temp = [];
            foreach ($response as $res) {
                $temp[] = array(
                    GeneralConstants::TASKRULEID => $res['ServiceID_0'],
                    'TaskRuleName' => $res['ServiceName_1'],
                    'LaborOrMaterials' => $res['sclr_2'],
                    'IntegrationQBDItemID' => $res['sclr_3']
                );
            }
            $response = $temp;

            return array(
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
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
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
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
            if (!array_key_exists(GeneralConstants::INTEGRATION_ID, $content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }

            $integrationID = $content[GeneralConstants::INTEGRATION_ID];

            //Check if the customer has enabled the integration or not and QBDSyncBilling is enabled.
            $integrationToCustomers = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncBillingEnabled($integrationID, $customerID);
            if (empty($integrationToCustomers)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if (!array_key_exists('Data', $content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_DATA);
            }

            $data = $content['Data'];

            // Traverse data to create/update mappings
            for ($i = 0; $i < count($data); $i++) {
                $itemsToTaskrules = $this->entityManager->getRepository('AppBundle:Integrationqbditemstoservices')->findOneBy(
                    array(
                        $this->serviceid => $data[$i][GeneralConstants::TASKRULEID],
                        'laborormaterials' => $data[$i][GeneralConstants::BILLTYPE]
                    )
                );

                if ($data[$i][GeneralConstants::INTEGRATION_QBD_ITEM_ID]) {
                    $integrationQBDItems = $this->entityManager->getRepository('AppBundle:Integrationqbditems')->findOneBy(array(
                            'integrationqbditemid' => $data[$i][GeneralConstants::INTEGRATION_QBD_ITEM_ID]
                        )
                    );

                    // Check if the integration QBD Customer is present. Or if the customer ID is valid or not
                    if (!$integrationQBDItems ||
                        ($integrationQBDItems !== null ? ($integrationQBDItems->getCustomerid()->getCustomerid() !== $customerID) : null)
                    ) {
                        throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATIONQBDITEMID);
                    }
                }

                // Integration QBD Items To TaskRules exist, then simply update the record with the new IntegrationQBDCustomerID
                if (!$itemsToTaskrules) {
                    // Create New Record
                    $taskRule = $this->entityManager->getRepository('AppBundle:Services')->findOneBy(array(
                            $this->serviceid => $data[$i][GeneralConstants::TASKRULEID]
                        )
                    );
                    if (!$taskRule ||
                        ($taskRule !== null ? ($taskRule->getCustomerid()->getCustomerid() !== $customerID) : null)
                    ) {
                        throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKRULE_ID);
                    }

                    $itemsToTaskrules = new Integrationqbditemstoservices();

                    $itemsToTaskrules->setIntegrationqbditemid($integrationQBDItems);
                    $itemsToTaskrules->setServiceid($taskRule);
                    $itemsToTaskrules->setLaborormaterials(
                        $data[$i][GeneralConstants::BILLTYPE]
                    );
                    $this->entityManager->persist($itemsToTaskrules);

                    $itemsToTaskrules1 = $this->entityManager->getRepository('AppBundle:Integrationqbditemstoservices')->findOneBy(
                        array(
                            $this->serviceid => $data[$i][GeneralConstants::TASKRULEID],
                            'laborormaterials' => !$data[$i][GeneralConstants::BILLTYPE]
                        )
                    );

                    // Check if !(BillType) is not present in either DB or the payload
                    if (!$itemsToTaskrules1) {
                        $key = $i;
                        for ($j = 0; $j < count($data); $j++) {
                            if (
                                ($i !== $j) &&
                                $data[$j][GeneralConstants::TASKRULEID] == $data[$i][GeneralConstants::TASKRULEID] &&
                                $data[$j]['BillType'] == !($data[$i]['BillType'])
                            ) {
                                $key = null;
                                break;
                            }
                        }
                        if ($key !== null) {
                            $itemsToTaskrules1 = new Integrationqbditemstoservices();

                            $itemsToTaskrules1->setIntegrationqbditemid(null);
                            $itemsToTaskrules1->setServiceid($taskRule);
                            $itemsToTaskrules1->setLaborormaterials(
                                !$data[$i][GeneralConstants::BILLTYPE]
                            );
                            $this->entityManager->persist($itemsToTaskrules1);
                        }
                    }

                } else {
                    // Update the record
                    if (!$data[$i][GeneralConstants::INTEGRATION_QBD_ITEM_ID]) {
                        $itemsToTaskrules->setIntegrationqbditemid(null);
                    } else {
                        $itemsToTaskrules->setIntegrationqbditemid($integrationQBDItems);
                    }
                    $this->entityManager->persist($itemsToTaskrules);
                }
            }
            $this->entityManager->flush();

            return array(
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION)
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