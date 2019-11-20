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
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => $employees
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
            $employeeToServicers = null;
            $integrationID = null;
            $count = null;
            $response = null;
            $flag = null;

            if(!array_key_exists('IntegrationID',$data)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }
            $integrationID = $data['IntegrationID'];

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
                            $count = $this->entityManager->getRepository('AppBundle:Integrationqbdemployeestoservicers')->CountStaffsJoinMatched($customerID,$staffTags, $department, $createDate);
                            if($count) {
                                $count = (int)$count[0][1];
                            }
                        }
                        $response = $this->entityManager->getRepository('AppBundle:Integrationqbdemployeestoservicers')->StaffsJoinMatched($customerID,$staffTags, $department, $createDate, $limit, $offset);
                        $flag = 1;
                    }

                    // If status is only set to not yet matched
                    if (!in_array(GeneralConstants::FILTER_MATCHED, $status) &&
                        in_array(GeneralConstants::FILTER_NOT_MATCHED, $status)
                    ) {
                        $count = $this->entityManager->getRepository('AppBundle:Servicers')->CountSyncServicers($customerID,$staffTags, $department, $createDate);
                        if($count) {
                            $count = (int)$count[0][1];
                        }
                        $response = $this->entityManager->getRepository('AppBundle:Servicers')->SyncServicers($customerID,$staffTags, $department, $createDate, $limit, $offset);
                        for($i=0;$i<count($response);$i++) {
                            $response[$i]["IntegrationQBDEmployeeID"] = null;
                        }
                        $flag = 1;
                    }
                }
            }

            // Default Case
            if(!$flag) {
                if($offset === 1) {
                    $count1 = $this->entityManager->getRepository('AppBundle:Integrationqbdemployeestoservicers')->CountStaffsJoinMatched($customerID,$staffTags, $department, $createDate);
                    if($count1) {
                        $count1 = (int)$count1[0][1];
                    }
                    $count2 = $this->entityManager->getRepository('AppBundle:Servicers')->CountSyncServicers($customerID,$staffTags, $department, $createDate);

                    if($count2) {
                        $count2 = (int)$count2[0][1];
                    }
                    $count = $count1 + $count2;
                }
                $response2 = null;
                $response = $this->entityManager->getRepository('AppBundle:Integrationqbdemployeestoservicers')->StaffsJoinMatched($customerID,$staffTags, $department, $createDate, $limit, $offset);
                $countResponse = count($response);
                if($countResponse < $limit) {
                    $limit = $limit-$countResponse;
                    $response2 = $this->entityManager->getRepository('AppBundle:Servicers')->SyncServicers($customerID,$staffTags, $department, $createDate, $limit, $offset);
                    for($i=0;$i<count($response2);$i++) {
                        $response2[$i]["IntegrationQBDEmployeeID"] = null;
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
            if(!array_key_exists('IntegrationID',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }

            $integrationID = $content['IntegrationID'];

            //Check if the customer has enabled the integration or not and QBDSyncTimeTracking is enabled.
            $integrationToEmployees = $this->entityManager->getRepository('AppBundle:Integrationstocustomers')->IsQBDSyncTimeTrackingEnabled($integrationID,$customerID);
            if(empty($integrationToEmployees)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INACTIVE);
            }

            if(!array_key_exists('Data',$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_DATA);
            }

            $data = $content['Data'];

            // Traverse data to create/update mappings
            for ($i = 0; $i < count($data); $i++) {
                $employeesTostaffs = $this->entityManager->getRepository('AppBundle:Integrationqbdemployeestoservicers')->findOneBy(
                    array(
                        'servicerid' => $data[$i][GeneralConstants::STAFFID]
                    )
                );

                $integrationQBDEmployees = $this->entityManager->getRepository('AppBundle:Integrationqbdemployees')->findOneBy(array(
                        'integrationqbdemployeeid' => $data[$i][GeneralConstants::INTEGRATION_QBD_EMPLOYEE_ID]
                    )
                );

                // Check if the integration QBD Customer is present. Or if the customer ID is valid or not
                if(!$integrationQBDEmployees ||
                    ($integrationQBDEmployees !== null?($integrationQBDEmployees->getCustomerid()->getCustomerid() !== $customerID):null)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_INTEGRATIONQBDEMPLOYEEID);
                }

                // Integration QBD Customers To Properties exist, then simply update the record with the new IntegrationQBDEmployeeID
                if (!$employeesTostaffs) {
                    // Create New Record
                    $staff = $this->entityManager->getRepository('AppBundle:Servicers')->findOneBy(array(
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
                    $employeesTostaffs->setIntegrationqbdemployeeid($integrationQBDEmployees);
                    $this->entityManager->persist($employeesTostaffs);
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
            $this->logger->error('Failed Saving mapped information due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }
}