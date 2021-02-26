<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 21/10/19
 * Time: 5:47 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationqbdemployeestoservicers;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
class MapStaffsService extends BaseService
{
    /**
     * @param $customerID
     * @return array
     */
    public function FetchEmployees($customerID)
    {
        try {
            $employees = $this->entityManager->getRepository('AppBundle:Integrationqbdemployees')->QBDEmployees($customerID);
            return array(
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
                GeneralConstants::DATA => $employees
            );
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed fetching employees due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $customerID
     * @param $data
     * @return array
     */
    public function MapStaffs($customerID, $data)
    {
        try {
            // Initialize variables
            $filters = null;
            $status = null;
            $staffTags = null;
            $department = null;
            $createDate = null;
            $limit = 10;
            $offset = 1;
            $integrationID = null;
            $count = null;
            $response = null;
            $flag = null;
            $unmatched = null;

            if(!array_key_exists(GeneralConstants::INTEGRATION_ID,$data)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $data[GeneralConstants::INTEGRATION_ID];

            //Check if the customer has enabled the integration or not and QBDSyncBilling is enabled.
            $integrationToEmployees = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncTimeTrackingEnabled($integrationID,$customerID);
            if(empty($integrationToEmployees)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if (!empty($data)) {
                $filters = array_key_exists('Filters', $data) ? $data['Filters'] : [];

                if (array_key_exists('StaffTag', $filters)) {
                    $staffTags = $this->entityManager->getRepository('AppBundle:Servicerstoemployeegroups')->ServicerstoEmployeeGroupsJoinMatched($filters['StaffTag']);
                }
                if (array_key_exists('Department', $filters)) {
                    $department = $this->entityManager->getRepository('AppBundle:Servicerstoservicegroups')->ServicerstoServiceGroupsJoinMatched($filters['Department']);
                }
                if (array_key_exists(GeneralConstants::CREATEDATE, $filters)) {
                    $createDate = $filters[GeneralConstants::CREATEDATE];
                }
                if (array_key_exists(GeneralConstants::PAGINATION, $data)) {
                    $limit = $data[GeneralConstants::PAGINATION]['Limit'];
                    $offset = $data[GeneralConstants::PAGINATION]['Offset'];
                }

                if (array_key_exists(GeneralConstants::STATUS_CAP, $filters)) {
                    $status = $filters[GeneralConstants::STATUS_CAP];


                    // If status is only set to matched
                    if (in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        !in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        if($offset === 1) {
                            $count = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONQBDEMPLOYEESTOSERVICERS)->CountStaffsJoinMatched($customerID,$staffTags, $department, $createDate);
                            if($count) {
                                $count = (int)$count[0][1];
                            }
                        }
                        $response = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONQBDEMPLOYEESTOSERVICERS)->StaffsJoinMatched($customerID,$staffTags, $department, $createDate, $limit, $offset);
                        $flag = 1;
                    }

                    // If status is only set to not yet matched
                    if (!in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        $unmatched = true;
                        $flag = 0;
                    }
                }
            }

            // Default Case
            if (!$flag) {
                if ($offset === 1) {
                    $count = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->CountSyncServicers($customerID, $staffTags, $department, $createDate,$unmatched);

                    if ($count) {
                        $count = (int)$count[0][1];
                    }
                }
                $response = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->SyncServicers($customerID, $staffTags, $department, $createDate, $limit, $offset,$unmatched);
            }

            return array(
                GeneralConstants::REASON_CODE => 0,
                GeneralConstants::REASON_TEXT => $this->translator->trans(GeneralConstants::SUCCESS_TRANSLATION),
                GeneralConstants::DATA => array(
                    'Count' => $count,
                    'Details' => $response
                )
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed mapping Staffs due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function MapStaffsToEmployees($customerID, $content)
    {
        try {
            if(!array_key_exists(GeneralConstants::INTEGRATION_ID,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }

            $integrationID = $content[GeneralConstants::INTEGRATION_ID];

            //Check if the customer has enabled the integration or not and QBDSyncTimeTracking is enabled.
            $integrationToEmployees = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncTimeTrackingEnabled($integrationID,$customerID);
            if(empty($integrationToEmployees)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if(!array_key_exists(GeneralConstants::DATA,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_DATA);
            }

            $data = $content[GeneralConstants::DATA];

            // Traverse data to create/update mappings
            for ($i = 0; $i < count($data); $i++) {
                $employeesTostaffs = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_INTEGRATIONQBDEMPLOYEESTOSERVICERS)->findOneBy(
                    array(
                        'servicerid' => $data[$i][GeneralConstants::STAFFID]
                    )
                );

                if($data[$i][GeneralConstants::INTEGRATION_QBD_EMPLOYEE_ID]) {
                    $integrationQBDEmployees = $this->entityManager->getRepository('AppBundle:Integrationqbdemployees')->findOneBy(array(
                            'integrationqbdemployeeid' => $data[$i][GeneralConstants::INTEGRATION_QBD_EMPLOYEE_ID]
                        )
                    );

                    // Check if the integration QBD Employee is present. Or if the employee ID is valid or not
                    if(!$integrationQBDEmployees ||
                        ($integrationQBDEmployees !== null?($integrationQBDEmployees->getCustomerid()->getCustomerid() !== $customerID):null)
                    ) {
                        throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATIONQBDEMPLOYEEID);
                    }
                }

                // Integration QBD Customers To Properties exist, then simply update the record with the new IntegrationQBDEmployeeID
                if (!$employeesTostaffs) {
                    // Create New Record
                    $staff = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_SERVICERS)->findOneBy(array(
                            'servicerid' => $data[$i][GeneralConstants::STAFFID]
                        )
                    );
                    if(!$staff ||
                        ($staff !== null?($staff->getCustomerid()->getCustomerid() !== $customerID):null)
                    ) {
                        throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STAFF_ID);
                    }

                    $employeesTostaffs = new Integrationqbdemployeestoservicers();

                    $employeesTostaffs->setIntegrationqbdemployeeid($integrationQBDEmployees);
                    $employeesTostaffs->setServicerid($staff);

                    $this->entityManager->persist($employeesTostaffs);
                } else {
                    // Update the record

                    if(!$data[$i][GeneralConstants::INTEGRATION_QBD_EMPLOYEE_ID]) {
                        $this->entityManager->remove($employeesTostaffs);
                    } else {
                        $employeesTostaffs->setIntegrationqbdemployeeid($integrationQBDEmployees);
                        $this->entityManager->persist($employeesTostaffs);
                    }
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
            $this->logger->error('Failed Saving mapped information for Staffs due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}