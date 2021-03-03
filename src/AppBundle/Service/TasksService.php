<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 8/2/20
 * Time: 10:13 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\GeneralConstants;
use AppBundle\Constants\ErrorConstants;
use AppBundle\DatabaseViews\AdditionalDefaultServicers;
use AppBundle\Entity\Tasks;
use AppBundle\Entity\Taskstoservicers;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class TasksService
 * @package AppBundle\Service
 */
class TasksService extends BaseService
{
    /**
     * Function to validate and get all tasks
     *
     * @param $authDetails
     * @param $queryParameter
     * @param $pathInfo
     * @param $tasksID
     *
     * @return mixed
     */
    public function getTasks($authDetails, $queryParameter, $pathInfo, $tasksID = null)
    {
        $returnData = array();
        try {
            //Get Tasks Repo
            $tasksRepo = $this->entityManager->getRepository(GeneralConstants::APPBUNDLE_TASKS);

            //cheking valid query parameters
            $checkParams = array_diff(array_keys($queryParameter), GeneralConstants::PARAMS);
            if (count($checkParams) > 0) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //cheking valid data of query parameter
            $validation = $this->serviceContainer->get('vrscheduler.public_general_service');
            $validationCheck = $validation->validationCheck($queryParameter);
            if (!$validationCheck) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            //check for limit option in query paramter
            (isset($queryParameter[GeneralConstants::PARAMS['PER_PAGE']]) ? $limit = $queryParameter[GeneralConstants::PARAMS['PER_PAGE']] : $limit = 20);

            //setting offset
            (isset($queryParameter[GeneralConstants::PARAMS['PAGE']]) ? $offset = $queryParameter[GeneralConstants::PARAMS['PAGE']] : $offset = 1);

            //getting task Detail
            $taskRulesData = $tasksRepo->getItems($authDetails['customerID'], $queryParameter, $tasksID, $offset, $limit);


            //return 404 if resource not found
            if (empty($taskRulesData)) {
                throw new HttpException(404);
            }

            //checking if more records are there to fetch from db
            $totalItems = (int)$tasksRepo->getItemsCount($authDetails['customerID'], $queryParameter, $tasksID, $offset)[0][1];

            //setting page count
            $totalPage = (int)ceil($totalItems / $limit);

            //Formating Date to utc ymd format
            for ($i = 0; $i < count($taskRulesData); $i++) {
                if (isset($taskRulesData[$i][GeneralConstants::CREATEDATE])) {
                    $taskRulesData[$i][GeneralConstants::CREATEDATE] = $taskRulesData[$i][GeneralConstants::CREATEDATE]->format('Ymd');
                }

                if (isset($taskRulesData[$i][GeneralConstants::TASKDATE])) {
                    $taskRulesData[$i][GeneralConstants::TASKDATE] = $taskRulesData[$i][GeneralConstants::TASKDATE]->format('Ymd');
                }

                if (isset($taskRulesData[$i]['CompleteConfirmedDate'])) {
                    $taskRulesData[$i]['CompleteConfirmedDate'] = $taskRulesData[$i]['CompleteConfirmedDate']->format('Y-m-d');
                }

                if (isset($taskRulesData[$i]['ApprovedDate'])) {
                    $taskRulesData[$i]['ApprovedDate'] = $taskRulesData[$i]['ApprovedDate']->format('Y-m-d');
                }

                if (isset($taskRulesData[$i]['TaskStartDate'])) {
                    $taskRulesData[$i]['TaskStartDate'] = $taskRulesData[$i]['TaskStartDate']->format('Y-m-d');
                }

                if (isset($taskRulesData[$i]['TaskCompleteByDate'])) {
                    $taskRulesData[$i]['TaskCompleteByDate'] = $taskRulesData[$i]['TaskCompleteByDate']->format('Y-m-d');
                }

                if (isset($taskRulesData[$i]['TaskTime'])) {
                    $taskRulesData[$i]['TaskTime'] = $taskRulesData[$i]['TaskTime']->format('H:i:s');
                }

                if (isset($taskRulesData[$i]['TaskStartTime'])) {
                    if ($taskRulesData[$i]['TaskStartTime'] !== 99 && $taskRulesData[$i]['TaskStartTimeMinutes'] !== 99) {
                        $taskRulesData[$i]['TaskStartTime'] = sprintf("%02d",$taskRulesData[$i]['TaskStartTime']).':'.sprintf("%02d",$taskRulesData[$i]['TaskStartTimeMinutes']);
                    } else {
                        $taskRulesData[$i]['TaskStartTime'] = null;
                    }
                    unset($taskRulesData[$i]['TaskStartTimeMinutes']);
                }

                if (isset($taskRulesData[$i]['TaskCompleteByTime'])) {
                    if ($taskRulesData[$i]['TaskCompleteByTime'] !== 99 && $taskRulesData[$i]['TaskCompleteByTimeMinutes'] !== 99) {
                        $taskRulesData[$i]['TaskCompleteByTime'] = sprintf("%02d",$taskRulesData[$i]['TaskCompleteByTime']) .':' . sprintf("%02d",$taskRulesData[$i]['TaskCompleteByTimeMinutes']);
                    } else {
                        $taskRulesData[$i]['TaskCompleteByTime'] = null;
                    }
                    unset($taskRulesData[$i]['TaskCompleteByTimeMinutes']);
                }

                // Set Task Name as ServiceName-TaskName
                $taskRulesData[$i]['TaskName'] = $taskRulesData[$i]['ServiceName'].' '.$taskRulesData[$i]['TaskName'];
                unset($taskRulesData[$i]['ServiceName']);

                // Add Property Details in a separate Object
                $properties = [];
//                $nextPropertyBooking = [];

                // Preg Replace Key Containing Properties_, npb_
                foreach ($taskRulesData[$i] as $key => $value) {
                    if (strpos($key, 'Properties_') !== false) {
                        if (strpos($key,'Date') && $taskRulesData[$i][$key]) {
                            $taskRulesData[$i][$key] = $taskRulesData[$i][$key]->format('Y-m-d');
                        }
                        $trimmedKey = explode('Properties_',$key);
                        $properties[$trimmedKey[1]] = $taskRulesData[$i][$key];
                        unset($taskRulesData[$i][$key]);
                    }
                    // Next PropertyBooking
                    /*if (strpos($key, 'npb_') !== false) {
                        if (($key === 'npb_CheckIn' || $key === 'npb_CheckOut' || $key === 'npb_CreateDate') && $taskRulesData[$i][$key]) {
                            $taskRulesData[$i][$key] = $taskRulesData[$i][$key]->format('Y-m-d');
                        }
                        $trimmedKey = explode('npb_',$key);
                        $nextPropertyBooking[$trimmedKey[1]] = $taskRulesData[$i][$key];
                        unset($taskRulesData[$i][$key]);
                    }*/
                }

                $staff = [];
                $staffs = $this->entityManager->getRepository('AppBundle:Taskstoservicers')->StaffDetailsInTasks($taskRulesData[$i]['TaskID']);
                foreach ($staffs as $key => $value) {
                    if ($staffs[$key]['StaffID']) {
                        $staffs[$key]['CreateDate'] = $staffs[$key]['CreateDate'] ? $staffs[$key]['CreateDate']->format('Y-m-d') : null;
                        $staffs[$key]['Phone'] = trim($staffs[$key]['Phone']);
                        $staff[] = $staffs[$key];
                    }
                }

                $taskRulesData[$i]['Property'] = $properties;
                $taskRulesData[$i]['Staff'] = $staff;
//                $taskRulesData[$i]['NextPropertyBooking'] = $nextPropertyBooking;
            }

            //Setting return Data
            $returnData['url'] = $pathInfo;
            ($totalItems <= $offset * $limit) ? $returnData['has_more'] = false : $returnData['has_more'] = true;
            $returnData['data'] = $taskRulesData;
            $returnData['page_count'] = $totalPage;
            $returnData['page_size'] = $limit;
            $returnData['page'] = $offset;
            $returnData['total_items'] = $totalItems;

        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::TASKS_API .
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
        return $returnData;
    }

    /**
     * Function to validate and create and update Tasks
     *
     * @param $content
     * @param $authDetails
     * @param $propertyBookingID
     *
     * @return array
     */
    public function insertTaskDetails($content, $authDetails)
    {
        $returnData = array();

        try {
            // parse Content
            $propertyID = $content[GeneralConstants::PROPERTY_ID];
            $taskRuleID = $content[GeneralConstants::TASKRULEID];

            // Check if a TaskRuleID is a valid ServiceID
            $checkValidTaskRule = $this->entityManager->getRepository('AppBundle:Servicestoproperties')->CheckValidTaskRule($taskRuleID,$propertyID,$authDetails);
            if (empty($checkValidTaskRule)) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            // Check if the input body is already present
            $checkValidTask = $this->entityManager->getRepository('AppBundle:Tasks')->CheckValidTask($content);
            if (!empty($checkValidTask)) {
                throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
            }

            // Insert Task
            $task = $this->CreateTask($content,$checkValidTaskRule);

            $thisDayOfWeek =  GeneralConstants::DAYOFWEEK[date('N')];

            // Assign the default Servicer
            $thisDefaultServicerID = $checkValidTaskRule[0]['DefaultServicerID'];

            // If the servicer is not working on this day, then assign a backup Servicer
            if(strpos((string)$checkValidTaskRule[0]['WorkDays'],(string)$thisDayOfWeek) === false) {
                $thisDefaultServicerID = $checkValidTaskRule[0]['BackupServicerID'.(string)$thisDayOfWeek];
            }

            // Get Payrate of that Servicer
            $payRate = 0;
            $servicerID = $this->entityManager->getRepository('AppBundle:Servicers')->find($thisDefaultServicerID);
            if ($servicerID) {
                $payRate = $servicerID->getPayrate();
            }

            // Insert Lead Employee
            $tasksToServicers = new Taskstoservicers();
            $tasksToServicers->setTaskid($task);
            $tasksToServicers->setServicerid($servicerID ? $servicerID : null);
            $tasksToServicers->setIslead(true);
            $tasksToServicers->setPiecepay($checkValidTaskRule[0]['PiecePay']);
            $tasksToServicers->setPayrate($payRate);
            $tasksToServicers->setPaytype($checkValidTaskRule[0]['PayType']);

            // persist taskstoservicers
            $this->entityManager->persist($tasksToServicers);
            $this->entityManager->flush();

            $response['TasksToServicers'] = $tasksToServicers->getTasktoservicerid();
            $response['DefaultServicerID'] = $thisDefaultServicerID;

            // GET ADDITIONAL EMPLOYEES
            $rsAdditionalServicers = 'SELECT ServicerID,ServiceToPropertyID,BackupServicerID7,BackupServicerID1,BackupServicerID2,BackupServicerID3,BackupServicerID4,BackupServicerID5,BackupServicerID6,WorkDays,PiecePay FROM ('.AdditionalDefaultServicers::vAdditionalDefaultServicers.') as S where S.ServiceToPropertyID='.$checkValidTaskRule[0]['ServiceToPropertyID'].' ORDER BY S.AdditionalDefaultServicerID';
            $rsAdditionalServicers = $this->entityManager->getConnection()->prepare($rsAdditionalServicers);
            $rsAdditionalServicers->execute();
            $rsAdditionalServicers = $rsAdditionalServicers->fetchAll();

            if (!empty($rsAdditionalServicers)) {
                foreach ($rsAdditionalServicers as $rsAdditionalServicer) {
                    // Assign the default Servicer
                    $thisDefaultServicerID = $rsAdditionalServicer['DefaultServicerID'];

                    // If the servicer is not working on this day, then assign a backup Servicer
                    if(strpos((string)$rsAdditionalServicer['WorkDays'],(string)$thisDayOfWeek) === false) {
                        $thisDefaultServicerID = $rsAdditionalServicer['BackupServicerID'.(string)$thisDayOfWeek];
                    }

                    // Servicer Object
                    $servicerID = $this->entityManager->getRepository('AppBundle:Servicers')->find($thisDefaultServicerID);

                    // INSERT ADDITIONAL EMPLOYEE
                    $tasksToServicers = new Taskstoservicers();
                    $tasksToServicers->setTaskid($task);
                    $tasksToServicers->setServicerid($servicerID ? $servicerID : null);
                    $tasksToServicers->setIslead(false);
                    $tasksToServicers->setPiecepay($rsAdditionalServicer['PiecePay']);

                    // Persist $tasksToServicers
                    $this->entityManager->persist($tasksToServicers);
                    $response['AdditionalEmployees'][] = $tasksToServicers->getTasktoservicerid();
                }
                $this->entityManager->flush();
            }

            //setting return data
            $returnData[GeneralConstants::TASK_ID] = $task->getTaskid();
            $returnData['TaskName'] = $task->getTaskname();
            $returnData['TaskDescription'] = $task->getTaskdescription();
            $returnData['CreateDate'] = ($task->getCreatedate())->format('Ymd');
            $returnData['TaskStartDate'] = ($task->getTaskstartdate())->format('Ymd');
            $returnData['TaskStartTime'] = $task->getTaskstarttime();
            $returnData['TaskCompleteByDate'] = ($task->getTaskcompletebydate())->format('Ymd');
            $returnData['TaskCompleteByTime'] = $task->getTaskcompletebytime();
            $returnData['TaskDate'] = ($task->getTaskdate())->format('Ymd');
            $returnData['TaskTime'] = $task->getTasktime();
            $returnData[GeneralConstants::PROPERTY_ID] = $task->getPropertyid()->getPropertyid();
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error(GeneralConstants::TASKS_API .
                $exception->getMessage());
            throw new BadRequestHttpException(ErrorConstants::INVALID_REQUEST);
        }

        return $returnData;
    }

    /**
     * @param $content
     * @param $rsService
     * @return Tasks
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function CreateTask($content, $rsService)
    {
        $task = new Tasks();
        $task->setPropertybookingid(null);
        $task->setPropertyid($this->entityManager->getRepository('AppBundle:Properties')->find($content[GeneralConstants::PROPERTY_ID]));
//        $task->setIssueid($issues);
//            $task->setNextpropertybookingid();
//            $task->setParenttaskid(0);
        $task->setTasktype(9);
        $task->setTaskname($content['TaskName']);
        $task->setTaskstartdate(new \DateTime($content['TaskStartDate']));
        $task->setTaskstarttime($content['TaskStartTime']);
        $task->setTaskdate(new \DateTime($content['TaskDate']));
        $task->setTasktime($content['TaskTime']);
        $task->setTaskdatetime(new \DateTime('now'));
        $task->setTaskcompletebydate(new \DateTime($content['TaskCompleteByDate']));
        $task->setTaskcompletebytime($content['TaskCompleteByTime']);
        $task->setServiceid($content[GeneralConstants::TASKRULEID]);
        $task->setMintimetocomplete($rsService[0]['MinTimeToComplete']);
        $task->setMaxtimetocomplete($rsService[0]['MaxTimeToComplete']);
        $task->setNumberofservicers($rsService[0]['NumberOfServicers']);
        $task->setMarked(1);
        $task->setEdited(1);
        $task->setIncludedamage((int)$rsService[0]['IncludeDamage']);
        $task->setIncludemaintenance((int)$rsService[0]['IncludeMaintenance']);
        $task->setIncludelostandfound((int)$rsService[0]['IncludeLostAndFound']);
        $task->setIncludesupplyflag((int)$rsService[0]['IncludeSupplyFlag']);
        $task->setIncludeservicernote((int)$rsService[0]['IncludeServicerNote']);
        $task->setNotifycustomeroncompletion((int)$rsService[0]['NotifyCustomerOnCompletion']);
        $task->setNotifycustomeronoverdue((int)$rsService[0]['NotifyServicerOnOverdue']);
        $task->setNotifycustomerondamage((int)$rsService[0]['NotifyCustomerOnDamage']);
        $task->setNotifycustomeronmaintenance((int)$rsService[0]['NotifyCustomerOnMaintenance']);
        $task->setNotifycustomeronservicernote((int)$rsService[0]['NotifyCustomerOnServicerNote']);
        $task->setNotifycustomeronlostandfound((int)$rsService[0]['NotifyCustomerOnLostAndFound']);
        $task->setNotifycustomeronsupplyflag((int)$rsService[0]['NotifyCustomerOnSupplyFlag']);
        $task->setIncludetoownernote((int)$rsService[0]['IncludeToOwnerNote'] === 1 ? true : false);
        $task->setDefaulttoownernote(trim($rsService[0]['DefaultToOwnerNote']));
        $task->setNotifyowneroncompletion($rsService[0]['NotifyOwnerOnCompletion']);
        $task->setAllowshareimageswithowners((int)$rsService[0]['AllowShareImagesWithOwners'] === 1 ? true : false);
        $task->setNotifyserviceronoverdue($rsService[0]['NotifyServicerOnOverdue']);
        $task->setNotifycustomeronnotyetdone($rsService[0]['NotifyCustomerOnNotYetDone']);
        $task->setNotifyserviceronnotyetdone($rsService[0]['NotifyServicerOnNotYetDone']);
        $task->setTaskdescription($content['TaskDescription']);
        $task->setBillable($rsService[0]['Billable']);
        $task->setAmount($rsService[0]['Amount'] ? $rsService[0]['Amount'] : 0);
        $task->setExpenseamount($rsService[0]['ExpenseAmount'] ? $rsService[0]['ExpenseAmount'] : 0);
//            $task->setPropertyitemid();
        $task->setCreatedbyservicerid($rsService[0]['DefaultServicerID']);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        // Return Task Object
        return $task;
    }
}