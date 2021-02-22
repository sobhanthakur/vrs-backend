<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 4/11/19
 * Time: 11:45 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\Entity\Integrationqbdbillingrecords;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class BillingApprovalService
 * @package AppBundle\Service
 */
class BillingApprovalService extends BaseService
{
    /**
     * @param $customerID
     * @param $data
     * @return array
     */
    public function MapTasks($customerID, $data)
    {
        try {
            // Initialize variables
            $filters = null;
            $properties = null;
            $createDate = null;
            $completedDate = null;
            $limit = 10;
            $offset = 1;
            $response = null;
            $integrationID = null;
            $count = null;
            $filters = [];
            $status = [];
            $completedDate = [];
            $timezones = [];

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
                $regions = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->GetAllTimeZones($customerID);
                $timezones = $this->StartDateCalculation($regions,$integrationToCustomers[0]['startdate']);

                $filters = array_key_exists('Filters', $data) ? $data['Filters'] : [];

                if (array_key_exists('Property', $filters)) {
                    $properties = $filters['Property'];
                }
                if (array_key_exists(GeneralConstants::COMPLETED_DATE, $filters) && !empty($filters[GeneralConstants::COMPLETED_DATE]['From']) && !empty($filters[GeneralConstants::COMPLETED_DATE]['To'])) {
                    $completedDate = $filters[GeneralConstants::COMPLETED_DATE];
                    $completedDate = $this->CompletedDateRequestCalculation($regions,$completedDate);
                }
                if (array_key_exists(GeneralConstants::CREATEDATE, $filters)) {
                    $createDate = $filters[GeneralConstants::CREATEDATE];
                }
                if (array_key_exists(GeneralConstants::PAGINATION, $data)) {
                    $limit = $data[GeneralConstants::PAGINATION]['Limit'];
                    $offset = $data[GeneralConstants::PAGINATION]['Offset'];
                }
            }

            if (array_key_exists(GeneralConstants::STATUS_CAP, $filters)) {
                $status = $filters[GeneralConstants::STATUS_CAP];
            }

            if ($offset === 1) {
                $count = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->CountMapTasks($customerID, $properties, $createDate, $completedDate, $timezones, $status);
                if ($count) {
                    $count = (int)$count[0][1];
                }
            }

            $response = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->MapTasks($customerID, $properties, $createDate, $completedDate, $timezones, $limit, $offset, $status);
            $response = $this->processResponse($response);

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
            $this->logger->error('Failed fetching tasks due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $customerID
     * @param $content
     * @return array
     */
    public function ApproveBilling($customerID, $content)
    {
        try {
            if(!array_key_exists(GeneralConstants::INTEGRATION_ID,$content)) {
                throw new UnprocessableEntityHttpException(ErrorConstants::EMPTY_INTEGRATION_ID);
            }

            $integrationID = $content[GeneralConstants::INTEGRATION_ID];

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

                // Check If status is correct or not
                if(
                ($data[$i][GeneralConstants::STATUS_CAP] !== 0) &&
                ($data[$i][GeneralConstants::STATUS_CAP] !== 1) &&
                ($data[$i][GeneralConstants::STATUS_CAP] !== 2)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_STATUS);
                }

                $billingRecords = $this->entityManager->getRepository('AppBundle:Integrationqbdbillingrecords')->findOneBy(
                    array(
                        'taskid' => $data[$i][GeneralConstants::TASK_ID]
                    )
                );

                // Throw Exception if the the record's txnID is not null
                if($billingRecords !== null?($billingRecords->getTxnid() !== null):null) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
                }

                $tasks = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS)->findOneBy(array(
                        'taskid' => $data[$i][GeneralConstants::TASK_ID]
                    )
                );

                if(!$tasks ||
                    ($tasks !== null?($tasks->getPropertyid()->getCustomerid()->getCustomerid() !== $customerID):null)
                ) {
                    throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_TASKID);
                }

                // If Integration QBD Billing Record exist, then simply update the record with the new Status
                if (!$billingRecords) {
                    // Create New Record

                    $billingRecords = new Integrationqbdbillingrecords();

                    $billingRecords->setTaskid($tasks);
                    $billingRecords->setStatus($data[$i][GeneralConstants::STATUS_CAP]);

                    $this->entityManager->persist($billingRecords);
                } else {
                    // Update the record
                    $billingRecords->setStatus($data[$i][GeneralConstants::STATUS_CAP]);
                    $this->entityManager->persist($billingRecords);
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
            $this->logger->error('Failed Saving Billing Approval due to : ' .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * Converts the Completed Date filter to UTC and query in the DB
     * @param $regions
     * @param $completedDateRequest
     * @return array|null
     */
    public function CompletedDateRequestCalculation($regions, $completedDateRequest)
    {
        $response = [];
        for($i=0;$i<count($regions);$i++) {
            $timeZoneLocal = new \DateTimeZone($regions[$i]['Region']);
            $fromLocal = new \DateTime($completedDateRequest['From'],$timeZoneLocal);
            $toLocal = new \DateTime($completedDateRequest['To'],$timeZoneLocal);

            $timeZoneUTC = new \DateTimeZone('UTC');
            $fromUTC = $fromLocal->setTimezone($timeZoneUTC);
            $toUTC = $toLocal->setTimezone($timeZoneUTC);
            $response[$i]['From'] = $fromUTC;
            $response[$i]['To'] = $toUTC->modify('+1 day');

        }
        return $response;
    }

    /**
     * Fetches Records Based on completedDate>=StartDate
     * @param $regions
     * @param \DateTime $startDate
     * @return array|null
     */
    public function StartDateCalculation($regions, $startDate)
    {
        $response = [];
        for($i=0;$i<count($regions);$i++) {
            $region = $regions[$i]['Region'];
            $timeZoneLocal = new \DateTimeZone($region);
            $startDateLocal = new \DateTime($startDate->format('Y-m-d'),$timeZoneLocal);
            $timeZoneUTC = new \DateTimeZone('UTC');
            $response[] = $startDateLocal->setTimezone($timeZoneUTC);
        }
        return $response;
    }

    /**
     * @param $response
     * @return mixed
     */
    public function processResponse($response)
    {
        for ($i = 0; $i < count($response); $i++) {
            if ($response[$i][GeneralConstants::STATUS_CAP] === null) {
                $response[$i]["Status"] = 2;
            }
            $time = $this->TimeZoneCalculation($response[$i]['TimeZoneRegion'], $response[$i]['CompleteConfirmedDate']);
            $response[$i]["CompleteConfirmedDate"] = $time;
            unset($response[$i]['TimeZoneRegion']);
        }
        return $response;
    }


    /**
     * @param $region
     * @param \DateTime $completeConfirmedDate
     * @return mixed
     */
    public function TimeZoneCalculation($region, $completeConfirmedDate)
    {
        $newTimeZone = new \DateTimeZone($region);
        $completeConfirmedDate->setTimezone($newTimeZone);
        return $completeConfirmedDate;

    }
}