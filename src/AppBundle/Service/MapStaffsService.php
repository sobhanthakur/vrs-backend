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
            $matchStatus = 2;
            $integrationID = null;

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
                if (array_key_exists('Status', $filters)) {
                    $status = $filters['Status'];
                    $employeeToServicers = $this->entityManager->getRepository('AppBundle:Integrationqbdemployeestoservicers')->StaffsJoinMatched($customerID);

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
            }

            $servicers = $this->entityManager->getRepository('AppBundle:Servicers')->SyncServicers($employeeToServicers, $staffTags, $department, $createDate, $limit, $offset, $customerID, $matchStatus);
            return array(
                'ReasonCode' => 0,
                'ReasonText' => $this->translator->trans('api.response.success.message'),
                'Data' => $servicers
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
}