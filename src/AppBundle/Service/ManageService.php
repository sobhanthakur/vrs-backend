<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 8/5/20
 * Time: 1:08 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\DatabaseViews\AdditionalDefaultServicers;
use AppBundle\DatabaseViews\ServicesToProperties;
use AppBundle\Entity\Issueandtaskimages;
use AppBundle\Entity\Issueandtaskimagestotasks;
use AppBundle\Entity\Issues;
use AppBundle\Entity\Servicers;
use AppBundle\Entity\Tasks;
use AppBundle\Entity\Taskstoservicers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class ManageService
 * @package AppBundle\Service
 */
class ManageService extends BaseService
{
    /**
     * @var array
     */
    private $notification = [];
    /**
     * @param $servicerID
     * @param $content
     * @return array
     */
    public function SubmitIssue($servicerID, $content)
    {
        $response = [];
        try {
            $taskID = null;

            if (array_key_exists('TaskID',$content) && $content['TaskID']) {
                $taskID = $content['TaskID'];
            }
            $task = null;

            array_key_exists('DateTime',$content) ? $dateTime = $content['DateTime'] : $dateTime='now';
            $currentDate = new \DateTime($dateTime);
            $propertyID = $content['PropertyID'];
            $propertyObj = $this->entityManager->getRepository('AppBundle:Properties')->find($propertyID);

            // get all issues submitted from this task in the last one minute
//            $issues = $this->entityManager->getRepository('AppBundle:Issues')->GetIssuesFromLastOneMinute($content['IssueType'],$content['Issue']);
//            if (!empty($issues)) {
//                throw new UnprocessableEntityHttpException(ErrorConstants::TRY1MINLATER);
//            }

            $servicer = $this->entityManager->getRepository('AppBundle:Servicers')->findOneBy(array('servicerid'=>$servicerID));

            // Create a new Issue
            $issues = new Issues();
            $issues->setIssuetype($content['IssueType']);
            $issues->setIssue($content['Issue']);
            $issues->setUrgent((int)$content['Urgent'] === 1 ? true : false);
            $issues->setPropertyid($propertyObj);
            $issues->setNotes($content['IssueDescription']);
            $issues->setCreatedate($currentDate);
            if ($taskID) {
                $issues->setFromtaskid($this->entityManager->getRepository('AppBundle:Tasks')->findOneBy(array('taskid'=>$taskID)));
            }
            $issues->setSubmittedbyservicerid($servicer);

            // Persist and flush Issue
            $this->entityManager->persist($issues);
            $this->entityManager->flush();

            // If ServiceID is present And Issue IS is present then create a task
            if ($issues && array_key_exists('FormServiceID',$content) ? $content['FormServiceID'] !== '' : null) {
                // Services To Properties
                $fields = 'ServiceToPropertyID,DefaultServicerID,BackupServicerID1,BackupServicerID2,BackupServicerID3,BackupServicerID4,BackupServicerID5,BackupServicerID6,BackupServicerID7,WorkDays,MinTimeToComplete,MaxTimeToComplete,NumberOfServicers,IncludeDamage,IncludeMaintenance,IncludeLostAndFound,IncludeSupplyFlag,IncludeServicerNote,NotifyCustomerOnCompletion,NotifyCustomerOnOverdue,NotifyCustomerOnDamage,NotifyCustomerOnMaintenance,NotifyCustomerOnServicerNote,NotifyCustomerOnLostAndFound,NotifyCustomerOnSupplyFlag,IncludeToOwnerNote,DefaultToOwnerNote,NotifyOwnerOnCompletion,AllowShareImagesWithOwners,NotifyServicerOnOverdue,NotifyCustomerOnNotYetDone,NotifyServicerOnNotYetDone,Billable,Amount,ExpenseAmount,PiecePay,PayType';
                $rsService = 'Select '.$fields.' from ('.ServicesToProperties::vServicesToProperties.') AS sp where sp.ServiceID='.$content['FormServiceID'].' AND sp.PropertyID='.$content['PropertyID'];
                $rsService = $this->entityManager->getConnection()->prepare($rsService);
                $rsService->execute();
                $rsService = $rsService->fetchAll();

                // Create Task
                $task = $this->CreateTask($content,$servicerID,$issues,$rsService,$propertyObj,$currentDate,$servicer);
                $response['TaskID'] = $task->getTaskid();

                $thisDayOfWeek =  GeneralConstants::DAYOFWEEK[date('N')];

                // Assign the default Servicer
                $thisDefaultServicerID = $rsService[0]['DefaultServicerID'];

                // If the servicer is not working on this day, then assign a backup Servicer
                if(strpos((string)$rsService[0]['WorkDays'],(string)$thisDayOfWeek) === false) {
                    $thisDefaultServicerID = $rsService[0]['BackupServicerID'.(string)$thisDayOfWeek];
                }

                // Get Payrate of that Servicer
                $payRate = 0;
                $servicerObj = $this->entityManager->getRepository('AppBundle:Servicers')->find($thisDefaultServicerID);
                if ($servicerObj) {
                    $payRate = $servicerObj->getPayrate();
                }

                // Insert Lead Employee
                $tasksToServicers = new Taskstoservicers();
                $tasksToServicers->setTaskid($task);
                $tasksToServicers->setServicerid($servicerObj ? $servicerObj : null);
                $tasksToServicers->setIslead(true);
                $tasksToServicers->setPiecepay($rsService[0]['PiecePay']);
                $tasksToServicers->setPayrate($payRate);
                $tasksToServicers->setPaytype($rsService[0]['PayType']);
                $tasksToServicers->setCreatedate($currentDate);

                // persist taskstoservicers
                $this->entityManager->persist($tasksToServicers);
                $this->entityManager->flush();

                $response['TasksToServicers'] = $tasksToServicers->getTasktoservicerid();
                $response['DefaultServicerID'] = $thisDefaultServicerID;

                // GET ADDITIONAL EMPLOYEES
                $rsAdditionalServicers = 'SELECT ServicerID,ServiceToPropertyID,BackupServicerID7,BackupServicerID1,BackupServicerID2,BackupServicerID3,BackupServicerID4,BackupServicerID5,BackupServicerID6,WorkDays,PiecePay FROM ('.AdditionalDefaultServicers::vAdditionalDefaultServicers.') as S where S.ServiceToPropertyID='.$rsService[0]['ServiceToPropertyID'].' ORDER BY S.AdditionalDefaultServicerID';
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
                        $servicerObj = $this->entityManager->getRepository('AppBundle:Servicers')->find($thisDefaultServicerID);

                        // INSERT ADDITIONAL EMPLOYEE
                        $tasksToServicers = new Taskstoservicers();
                        $tasksToServicers->setTaskid($task);
                        $tasksToServicers->setServicerid($servicerObj ? $servicerObj : null);
                        $tasksToServicers->setIslead(false);
                        $tasksToServicers->setPiecepay($rsAdditionalServicer['PiecePay']);
                        $tasksToServicers->setCreatedate($currentDate);

                        // Persist $tasksToServicers
                        $this->entityManager->persist($tasksToServicers);
                        $response['AdditionalEmployees'][] = $tasksToServicers->getTasktoservicerid();
                    }
                    $this->entityManager->flush();
                }

            }

            // Save Images
            foreach ($content['Images'] as $image) {
                $issueImage = new Issueandtaskimages();
                $issueImage->setImageName($image['Image']);
                $issueImage->setIssueID($issues);
                $issueImage->setCreateDate($currentDate);
                $issueImage->setPropertyID($propertyObj);
                $this->entityManager->persist($issueImage);
                if ($task) {
                    $issueImageToTask = new Issueandtaskimagestotasks();
                    $issueImageToTask->setTaskId($task);
                    $issueImageToTask->setIssueAndtaskimageid($issueImage);
                    $this->entityManager->persist($issueImageToTask);
                }
            }
            $this->entityManager->flush();

            $response['IssueID'] = $issues->getIssueid();


            // Send notification for the issue created
            $issueNotification = array(
                'MessageID' => 22,
                'CustomerID' => $propertyObj->getCustomerid()->getCustomerid(),
                'TaskID' => $taskID,
                'IssueID' => $issues->getIssueid(),
                'OwnerID' => 0,
                'SendToMaintenanceStaff' => 1,
                'SendToManagers' => 1,
                'ServicerID' => $servicerID
            );
            $result = $this->serviceContainer->get('vrscheduler.notification_service')->CreateIssueNotification($issueNotification,$currentDate);
            $this->notification['IssueNotificationID'] = $result;

            return array(
                GeneralConstants::REASON_TEXT => GeneralConstants::SUCCESS,
                'TaskInfo' => $response,
                'Notification' => $this->notification
            );
        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed submitting Issue form '.
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        }
    }

    /**
     * @param $content
     * @param $servicerID
     * @param $issues
     * @param $rsService
     * @param $propertyObj
     * @param $currentDate
     * @param Servicers $servicer
     * @return Tasks
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function CreateTask($content, $servicerID, $issues, $rsService, $propertyObj, $currentDate, $servicer)
    {
        $localTime = $this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($servicer->getTimezoneid()->getRegion(),$content['DateTime']);
        if ($rsService) {
            $task = new Tasks();
            $task->setPropertybookingid(null);
            $task->setPropertyid($propertyObj);
            $task->setIssueid($issues);
//            $task->setNextpropertybookingid();
//            $task->setParenttaskid(0);
            $task->setTasktype(9);
            $task->setTaskname($content['Issue']);
            $task->setTaskstartdate($localTime);
            $task->setTaskstarttime(99);
            $task->setTaskdate($localTime);
            $task->setTasktime(99);
            $task->setTaskdatetime($localTime);
            $task->setTaskcompletebydate($this->serviceContainer->get('vrscheduler.util')->UtcToLocalToUtcConversion($servicer->getTimezoneid()->getRegion(),$content['DateTime'])->modify('+5 day'));
            $task->setTaskcompletebytime(99);
            $task->setServiceid($content['FormServiceID']);
            $task->setMintimetocomplete($rsService[0]['MinTimeToComplete']);
            $task->setMaxtimetocomplete($rsService[0]['MaxTimeToComplete']);
            $task->setNumberofservicers($rsService[0]['NumberOfServicers']);
            $task->setMarked(1);
            $task->setEdited(1);
            $task->setIncludedamage((int)$rsService[0]['IncludeDamage'] === 1 ? true : false);
            $task->setIncludemaintenance((int)$rsService[0]['IncludeMaintenance'] === 1 ? true : false);
            $task->setIncludelostandfound((int)$rsService[0]['IncludeLostAndFound'] === 1 ? true : false);
            $task->setIncludesupplyflag((int)$rsService[0]['IncludeSupplyFlag'] === 1 ? true : false);
            $task->setIncludeservicernote((int)$rsService[0]['IncludeServicerNote'] === 1 ? true : false);
            $task->setNotifycustomeroncompletion($rsService[0]['NotifyCustomerOnCompletion']);
            $task->setNotifycustomeronoverdue($rsService[0]['NotifyServicerOnOverdue']);
            $task->setNotifycustomerondamage($rsService[0]['NotifyCustomerOnDamage']);
            $task->setNotifycustomeronmaintenance($rsService[0]['NotifyCustomerOnMaintenance']);
            $task->setNotifycustomeronservicernote($rsService[0]['NotifyCustomerOnServicerNote']);
            $task->setNotifycustomeronlostandfound($rsService[0]['NotifyCustomerOnLostAndFound']);
            $task->setNotifycustomeronsupplyflag($rsService[0]['NotifyCustomerOnSupplyFlag']);
            $task->setIncludetoownernote((int)$rsService[0]['IncludeToOwnerNote'] === 1 ? true : false);
            $task->setDefaulttoownernote(trim($rsService[0]['DefaultToOwnerNote']));
            $task->setNotifyowneroncompletion($rsService[0]['NotifyOwnerOnCompletion']);
            $task->setAllowshareimageswithowners((int)$rsService[0]['AllowShareImagesWithOwners'] === 1 ? true : false);
            $task->setNotifyserviceronoverdue($rsService[0]['NotifyServicerOnOverdue']);
            $task->setNotifycustomeronnotyetdone($rsService[0]['NotifyCustomerOnNotYetDone']);
            $task->setNotifyserviceronnotyetdone($rsService[0]['NotifyServicerOnNotYetDone']);
            $task->setTaskdescription($content['IssueDescription']);
            $task->setBillable($rsService[0]['Billable']);
            $task->setAmount($rsService[0]['Amount'] ? $rsService[0]['Amount'] : 0);
            $task->setExpenseamount($rsService[0]['ExpenseAmount'] ? $rsService[0]['ExpenseAmount'] : 0);
//            $task->setPropertyitemid();
            $task->setCreatedbyservicerid($servicerID);
            $task->setCreatedate($currentDate);
            $task->setSchedulechangedate($currentDate);
//            $task->setTaskdescriptionimage1($content['Images'][0]['Image']);
//            $task->setTaskdescriptionimage2($content['Images'][1]['Image']);
//            $task->setTaskdescriptionimage3($content['Images'][2]['Image']);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $notification = array(
                'MessageID' => 24,
                'CustomerID' => $propertyObj->getCustomerid()->getCustomerid(),
                'TaskID' => $task
            );

            // Send Notification
            $notificationResult = $this->serviceContainer->get('vrscheduler.notification_service')->CreateTaskNotification($notification,$currentDate);

            // Return Task Object
            $this->notification['TaskNotification'] = $notificationResult;

            return $task;
        }
    }

    /**
     * @param Request $request
     * @return array | null
     */
    public function UploadImage($request)
    {
        $path = $this->serviceContainer->getParameter('filepath');
        $filename = null;
        $response = [];
        try {
            $customerID = $request->get('CustomerID');
            $request->files->get('Image') ? $image = $request->files->get('Image') : $image=null;

            if (!$customerID || !$image) {
                throw new UnprocessableEntityHttpException(ErrorConstants::INVALID_PAYLOAD);
            }

            // Move the file to a corresponding path
            $filename = $image->getClientOriginalName();
            $image->move($path,$filename);

            // aws Parameters
            $aws = $this->serviceContainer->getParameter('aws');

            // Connect to s3
            $s3 = new \Aws\S3\S3Client([
                'region'  => $aws['region'],
                'version' => 'latest',
                'credentials' => [
                    'key'    => $aws['key'],
                    'secret' => $aws['secret'],
                ]
            ]);

            // Push Image
            $result = $s3->putObject([
                'Bucket' => $aws['bucket_name'],
                'ACL' => 'public-read',
                'Key'    => $customerID.'/'.$filename,
                'SourceFile' => $path.'/'.$filename
            ]);

            if ($result) {
                if ($result['@metadata']['statusCode'] === 200) {
                    $response['ImageURL'] = $result['@metadata']['effectiveUri'];
                }
            }

            return $response;

        } catch (UnprocessableEntityHttpException $exception) {
            throw $exception;
        } catch (HttpException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->logger->error('Failed Uploading Image due to: '.
                $exception->getMessage());
            throw new HttpException(500, ErrorConstants::INTERNAL_ERR);
        } finally {
            // Delete the Image from Local Path
            if ($filename) {
                unlink($path.'/'.$filename);
            }
        }
    }
}