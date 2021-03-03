<?php
/**
 * Created by PhpStorm.
 * User: Sobhan
 * Date: 4/11/19
 * Time: 12:09 PM
 */

namespace AppBundle\Repository;


use AppBundle\Constants\ErrorConstants;
use AppBundle\Constants\GeneralConstants;
use AppBundle\DatabaseViews\Tasks;
use AppBundle\DatabaseViews\TaskWithServicers;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class TasksRepository
 * @package AppBundle\Repository
 */
class TasksRepository extends EntityRepository
{
    /**
     * @param $customerID
     * @return mixed
     */
    public function GetAllTimeZones($customerID)
    {
        $result = null;

        $result = $this
            ->createQueryBuilder('t2')
            ->select('DISTINCT(t.region) AS Region')
            ->where('t2.active=1')
            ->innerJoin('t2.propertyid', 'p2')
            ->innerJoin('p2.regionid', 'r')
            ->innerJoin('r.timezoneid', 't')
            ->andWhere('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('t2.billable=1')
            ->andWhere('t2.completeconfirmeddate IS NOT NULL');

        return $result
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $customerID
     * @param $properties
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function MapTasks($customerID, $properties, $createDate, $completedDate, $timezones, $limit, $offset, $new)
    {
        $result = $this
            ->createQueryBuilder('t2')
            ->select('DISTINCT(t2.taskid) as TaskID, s2.serviceid AS ServiceID,s2.servicename AS ServiceName,b1.status AS Status,t2.taskname AS TaskName,p2.propertyid AS PropertyID,p2.propertyname AS PropertyName,t2.amount AS LaborAmount, t2.expenseamount AS MaterialAmount,t2.completeconfirmeddate AS CompleteConfirmedDate, t.region AS TimeZoneRegion')
            ->innerJoin('AppBundle:Services','s2',Expr\Join::WITH, 't2.serviceid=s2.serviceid');

        $result = $this->TrimMapTasks($result, $new, $properties, $completedDate, $timezones, $createDate, $customerID);

        $result->orderBy('t2.completeconfirmeddate', 'ASC');
        $result->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);
        return $result
            ->getQuery()
            ->execute();
    }

    /**
     * @param $customerID
     * @param $properties
     * @param $createDate
     * @param $completedDate
     * @param $timezones
     * @return mixed
     */
    public function CountMapTasks($customerID, $properties, $createDate, $completedDate, $timezones, $new)
    {
        $result = $this
            ->createQueryBuilder('t2')
            ->select('count(DISTINCT(t2.taskid))')
            ->innerJoin('AppBundle:Services', 's2', Expr\Join::WITH, 't2.serviceid=s2.serviceid');

        $result = $this->TrimMapTasks($result, $new, $properties, $completedDate, $timezones, $createDate, $customerID);

        return $result
            ->getQuery()
            ->execute();
    }

    /**
     * @param QueryBuilder $result
     * @param $new
     * @param $properties
     * @param $completedDate ,$timezones
     * @param $createDate
     * @param $customerID
     * @return mixed
     */
    public function TrimMapTasks($result, $new, $properties, $completedDate, $timezones, $createDate, $customerID)
    {
        $result
            ->innerJoin('t2.propertyid', 'p2')
            ->leftJoin('AppBundle:Integrationqbdbillingrecords', 'b1', Expr\Join::WITH, 'b1.taskid=t2.taskid')
            ->innerJoin('AppBundle:Integrationqbdcustomerstoproperties', 'e1', Expr\Join::WITH, 'e1.propertyid=p2.propertyid')
            ->innerJoin('AppBundle:Integrationqbditemstoservices', 'e2', Expr\Join::WITH, 'e2.serviceid=s2.serviceid')
            ->where('t2.active=1')
            ->andWhere('b1.txnid IS NULL')
            ->andWhere('b1.sentstatus IS NULL OR b1.sentstatus=0')
            ->innerJoin('p2.regionid', 'r')
            ->innerJoin('r.timezoneid', 't')
            ->andWhere('p2.customerid = :CustomerID')
            ->setParameter('CustomerID', $customerID)
            ->andWhere('p2.active=1')
            ->andWhere('t2.billable=1')
            ->andWhere('t2.completeconfirmeddate IS NOT NULL');
        if (!empty($timezones)) {
            $size = count($timezones);

            $query = 't2.completeconfirmeddate >= :TimeZone0';
            $result->setParameter('TimeZone0', $timezones[0]);
            for ($i = 1; $i < $size; $i++) {
                $query .= ' OR t2.completeconfirmeddate >= :TimeZone' . $i;
                $result->setParameter('TimeZone' . $i, $timezones[$i]);
            }
            $result->andWhere($query);
        }

        if (!empty($completedDate)) {
            $size = count($completedDate);
            $query = 't2.completeconfirmeddate BETWEEN :CompletedDateFrom0 AND :CompletedDateTo0';
            $result->setParameter('CompletedDateFrom0', $completedDate[0]['From']);
            $result->setParameter('CompletedDateTo0', $completedDate[0]['To']);
            for ($i = 1; $i < $size; $i++) {
                $query .= ' OR t2.completeconfirmeddate BETWEEN :CompletedDateFrom' . $i . ' AND :CompletedDateTo' . $i;
                $result->setParameter('CompletedDateFrom' . $i, $completedDate[$i]['From']);
                $result->setParameter('CompletedDateTo' . $i, $completedDate[$i]['To']);
            }
            $result->andWhere($query);
        }

        if ($new) {
            $condition1 = null;
            $condition2 = null;
            $condition3 = null;
            $condition = null;
            if (in_array(GeneralConstants::APPROVED, $new)) {
                $condition1 = 'b1.status=1';
                $condition = $condition1;
            }
            if (in_array(GeneralConstants::EXCLUDED, $new)) {
                $condition2 = $condition1 ? ' OR b1.status=0' : 'b1.status=0';
                $condition .= $condition2;
            }
            if (in_array(GeneralConstants::NEW, $new)) {
                $condition3 = $condition1 || $condition2 ? ' OR (b1.status IS NULL OR b1.status=2)' : 'b1.status IS NULL OR b1.status=2';
                $condition .= $condition3;
            }
            $result->andWhere($condition);
        }

        if ($properties) {
            $result->andWhere('p2.propertyid IN (:Properties)')
                ->setParameter('Properties', $properties);
        }

        if ($createDate) {
            $result->andWhere('t2.createdate BETWEEN :From AND :To')
                ->setParameter('From', $createDate['From'])
                ->setParameter('To', $createDate['To']);
        }

        return $result;
    }

    /**
     * Function to fetch task details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskID
     * @param $offset
     * @param $query
     * @param $limit
     *
     * @return array
     */
    public function fetchTasks($customerDetails, $queryParameter, $taskID, $offset, $query, $limit = null)
    {
        $sortOrder = array();

        $result = $this
            ->createQueryBuilder('t');

        //check for sort option in query paramter
        isset($queryParameter['sort']) ? $sortOrder = explode(',', $queryParameter['sort']) : null;

        //check if active is set
        isset($queryParameter['active']) ? $active = $queryParameter['active'] : null;

        // Check if flag1 and flag2 is set
        isset($queryParameter['flag1']) ? $flag1 = $queryParameter['flag1'] : null;
        isset($queryParameter['flag2']) ? $flag2 = $queryParameter['flag2'] : null;

        //check for task rule id in query paramter
        isset($queryParameter['taskruleid']) ? $taskRuleID = $queryParameter['taskruleid'] : $taskRuleID = null;

        //check for task rule id in query paramter
        isset($queryParameter['propertybookingid']) ? $propertyBookingID = $queryParameter['propertybookingid'] : $propertyBookingID = null;

        //check for property id in query paramter
        isset($queryParameter['propertyid']) ? $propertyID = $queryParameter['propertyid'] : $propertyID = null;

        //check for approved in query paramter
        isset($queryParameter['completed']) ? $completed = $queryParameter['completed'] : $completed = null;

        //check for approved in query paramter
        isset($queryParameter['approved']) ? $approved = $queryParameter['approved'] : $approved = null;

        //check for billable in query paramter
        isset($queryParameter['billable']) ? $billable = $queryParameter['billable'] : $billable = null;

        //check for approvedstartdate and approvedenddate in query paramter
        isset($queryParameter['approvedstartdate']) ? $approvedStartDate = $queryParameter['approvedstartdate'] : $approvedStartDate = null;
        isset($queryParameter['approvedenddate']) ? $approvedEndDate = $queryParameter['approvedenddate'] : $approvedEndDate = null;

        //check for completedstartdate and completedenddate in query paramter
        isset($queryParameter['completedstartdate']) ? $completedStartDate = $queryParameter['completedstartdate'] : $completedStartDate = null;
        isset($queryParameter['completedenddate']) ? $completedEndDate = $queryParameter['completedenddate'] : $completedEndDate = null;

        //check for taskstartdate and taskenddate in query paramter
        isset($queryParameter['taskstartdate']) ? $taskStartDate = $queryParameter['taskstartdate'] : $taskStartDate = null;
        isset($queryParameter['taskenddate']) ? $taskEndDate = $queryParameter['taskenddate'] : $taskEndDate = null;

        //condition to set query for all or some required fields
        $result->select($query);

        //condition to set sortorder
        if (sizeof($sortOrder) > 0) {
            foreach ($sortOrder as $field) {
                if ($field !== 'taskdateasc' && $field !== 'taskdatedesc') {
                    $result->addOrderBy('t.' . $field);
                }

                // If TaskDateAsc is present
                if ($field === 'taskdateasc') {
                    $result->addOrderBy('t.taskdatetime');
                }

                // If TaskDateDesc is present
                if ($field === 'taskdatedesc') {
                    $result->addOrderBy('t.taskdatetime','desc');
                }
            }
        }

        //condition to filter by property booking id
        if (isset($propertyBookingID)) {
            $result->andWhere('t.propertybookingid = (:PropertyBookingId)')
                ->setParameter('PropertyBookingId', $propertyBookingID);
        }

        //condition to filter by  task rule id
        if (isset($taskRuleID)) {
            $result->andWhere('t.serviceid =  (:TaskRuleID)')
                ->setParameter('TaskRuleID', $taskRuleID);
        }

        //condition to filter by  billable
        if (isset($billable)) {
            $result->andWhere('t.billable = (:Billable)')
                ->setParameter('Billable', $billable);
        }

        //condition to filter by task id
        if ($taskID) {
            $result->andWhere('t.taskid IN (:TaskID)')
                ->setParameter('TaskID', $taskID);
        }

        //condition to check for customer specific data
        if ($customerDetails) {
            $result->andWhere('p.customerid IN (:CustomerID)')
                ->setParameter('CustomerID', $customerDetails);
        }

        //condition to check for approved task
        if ($approved) {
            $result->andWhere('t.approved = (:Approved)')
                ->setParameter('Approved', $approved);
        }

        //condition to check for completed task
        if (isset($completed)) {
            if ($completed == 1) {
                $result->andWhere('t.completeconfirmeddate IS NOT NULL');
            } else {
                $result->andWhere('t.completeconfirmeddate IS NULL');
            }
        }

        //condition to filter by  approvedStartDate
        if ($approvedStartDate) {
            $approvedStartDate = date("Y-m-d", strtotime($approvedStartDate));
            $result->andWhere('t.approveddate >= (:ApprovedDate)')
                ->setParameter('ApprovedDate', $approvedStartDate);
        }
        //condition to filter by  approvedEndDate
        if ($approvedEndDate) {
            $approvedEndDate = date("Y-m-d", strtotime($approvedEndDate . ' +1 day'));
            $result->andWhere('t.approveddate <= (:ApprovedEndDate)')
                ->setParameter('ApprovedEndDate', $approvedEndDate);
        }

        //condition to filter by  completedStartDate
        if ($completedStartDate) {
            $completedStartDate = date("Y-m-d", strtotime($completedStartDate));
            $result->andWhere('t.completeconfirmeddate >= (:ConfirmationDate)')
                ->setParameter('ConfirmationDate', $completedStartDate);
        }
        //condition to filter by  $completedEndDate
        if ($completedEndDate) {
            $completedEndDate = date("Y-m-d", strtotime($completedEndDate . ' +1 day'));
            $result->andWhere('t.completeconfirmeddate <= (:ConfirmationDate)')
                ->setParameter('ConfirmationDate', $completedEndDate);
        }

        //condition to filter by taskStartDate
        if ($taskStartDate) {
            $taskStartDate = date("Y-m-d", strtotime($taskStartDate));
            $result->andWhere('t.taskdate >= (:TaskDate)')
                ->setParameter('TaskDate', $taskStartDate);
        }
        //condition to filter by  taskEndDate
        if ($taskEndDate) {
            $taskEndDate = date("Y-m-d", strtotime($taskEndDate));
            $result->andWhere('t.taskdate <= (:TaskEndDate)')
                ->setParameter('TaskEndDate', $taskEndDate);
        }

        //condition to filter by  Active
        if (isset($active)) {
            $result->andWhere('t.active =  (:Active)')
                ->setParameter('Active', $active);
        }

        if (isset($flag1)) {
            $result->andWhere('s.flag1 =  (:Flag1)')
                ->setParameter('Flag1', $flag1);
        }

        if (isset($flag2)) {
            $result->andWhere('s.flag2 =  (:Flag2)')
                ->setParameter('Flag2', $flag2);
        }

        //return task details
        return $result
            ->leftJoin('t.propertybookingid', 'pb')
            ->leftJoin('t.propertyid', 'p')
            ->leftJoin('AppBundle:Services','s',Expr\Join::WITH, 't.serviceid=s.serviceid')
            ->leftJoin('AppBundle:PropertyBookings','npb',Expr\Join::WITH, 't.nextpropertybookingid=npb.propertybookingid')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();
    }

    /**
     * Function to fetch task details
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskID
     * @param $offset
     * @param $limit
     *
     * @return array
     */
    public function getItems($customerDetails, $queryParameter, $taskID, $offset, $limit)
    {
        $query = "";
        $fields = array();

        //Get all task field
        $taskFields = GeneralConstants::TASKS_MAPPING;

        //check for fields option in query paramter
        (isset($queryParameter['fields'])) ? $fields = explode(',', $queryParameter['fields']) : $fields;

        //condition to set query for all or some required fields according to resquest
        if (sizeof($fields) > 0) {
            foreach ($fields as $field) {
                $query .= ',' . $taskFields[$field];
            }
        } else {
            $query .= implode(',', $taskFields);
        }
        $query = trim($query, ',');

        //return task results
        return $this->fetchTasks($customerDetails, $queryParameter, $taskID, $offset, $query, $limit);

    }

    /**
     * Function to get no. of task of the consumer
     *
     * @param $customerDetails
     * @param $queryParameter
     * @param $taskID
     * @param $offset
     *
     * @return array
     */
    public function getItemsCount($customerDetails, $queryParameter, $taskID, $offset)
    {
        $query = "count(t.taskid)";

        // Unset Sort Order IF Exists
        if(array_key_exists('sort',$queryParameter)) {
            unset($queryParameter['sort']);
        }
        return $this->fetchTasks($customerDetails, $queryParameter, $taskID, $offset, $query);

    }

    /**
     * @param $servicerID
     * @return mixed
     */
    public function FetchTasksForDashboard($servicerID, $servicers,$taskID=null)
    {
        // The dashboard should limit tasks to Servicers.ViewTasksWithinDays
        $viewTaskWithinDays = (int)$servicers[0]['ViewTaskWithinDays'];

        if ($viewTaskWithinDays === 0) {
            $viewTaskWithinDays = 7;
        }
        $min = min($viewTaskWithinDays,7);

        $now = (new \DateTime('now'));
        $now->setTimezone(new \DateTimeZone($servicers[0]['Region']));
        $today = $now->modify('+'.$min.' days')->format('Y-m-d');


        $result =  $this
            ->createQueryBuilder('t2');

        // Fetch Basic task details
        if ($taskID) {
            $result->select('t2.includehousekeeping AS IncludeHouseKeeping,t2.includeservicernote AS IncludeServicerNote,t2.serviceid AS ServiceID,IDENTITY(t2.propertyid) AS PropertyID,ts.accepteddate as AcceptedDate,t2.taskstartdate AS TaskStartDate,t2.taskid AS TaskID,t2.servicernotes AS ServicerNotes,t2.taskname AS TaskName,serviceid.servicename AS ServiceName,p2.propertyname AS PropertyName,t2.defaulttoownernote AS DefaultToOwnerNote,t2.toownernote AS ToOwnerNote,t2.includetoownernote AS IncludeToOwnerNote,t2.allowshareimageswithowners AS AllowShareImagesWithOwners,t2.includeurgentflag AS IncludeUrgentFlag,t2.includesupplyflag AS IncludeSupplyFlag,t2.includelostandfound AS IncludeLostAndFound,t2.includedamage AS IncludeDamage,t2.includemaintenance AS IncludeMaintenance');
        } else {
            $result->select('serviceid.shortdescription AS ShortDescription,ps.propertystatus AS PropertyStatus,serviceid.showPropertyStatusOnDashboards AS ShowPropertyStatusOnDashboards,ts.paytype AS PayType,s2.useslack AS UseSlack,c2.slackteamid AS SlackTeamID,p2.slackchannelid AS SlackChannelID,parenttask.taskdate AS ParentTaskDate,parenttask.completeconfirmeddate AS ParentCompleteConfirmedDate,parentservice.abbreviation AS ParentServiceAbbreviation,IDENTITY(t2.parenttaskid) AS ParenTaskID,t2.tasktime AS TaskTime,t2.tasktimeminutes AS TaskTimeMinutes,ts.piecepay AS PiecePay,t2.backtoback AS BackToBack,p2.sortorder,rgid.sortorder,r2.sortorder,t2.taskdatetime AS TaskDateTime,pb2.backtobackend AS BackToBackEnd,pb2.backtobackstart AS BackToBackStart,p2.staffdashboardnote AS StaffDashboardNote, t2.serviceid AS ServiceID,IDENTITY(s2.customerid) AS S_CustomerID,c2.customerid AS C_CustomerID,pb2.propertybookingid AS PropertyBookingID,t2.nextpropertybookingid AS NextPropertyBookingID,c2.email AS Email,p2.address AS Address,p2.doorcode AS DoorCode,p2.propertyfile AS PropertyFile,IDENTITY(t2.propertyid) AS PropertyID,serviceid.servicename AS ServiceName,p2.propertyname AS PropertyName,t2.taskdescription AS TaskDescription,t2.taskstarttimeminutes AS TaskStartTimeMinutes,t2.taskcompletebytimeminutes AS TaskCompleteByTimeMinutes,t2.taskcompletebytime AS TaskCompleteByTime,t2.taskstarttime AS TaskStartTime,t2.taskcompletebydate AS TaskCompleteByDate,t2.taskstartdate As TaskStartDate,ts.accepteddate as AcceptedDate,t2.taskid AS TaskID, t2.taskname AS TaskName, r2.region AS Region,r2.color AS RegionColor, p2.lat AS Lat, p2.lon AS Lon,t2.taskdate AS AssignedDate')
                // Task Description Details
                ->addSelect('pb2.globalnote AS GlobalNote,pb2.inglobalnote AS InGlobalNote, serviceid.tasktype AS TaskType, pb2.outglobalnote AS OutGlobalNote, ts.instructions AS Instructions, serviceid.showalltagsondashboards AS ShowAllTagsOnDashboards, pb2.bookingtags AS BookingTags, pb2.manualbookingtags AS ManualBookingTags,npb2.bookingtags AS NextBookingTags,npb2.manualbookingtags AS NextManualBookingTags,serviceid.showpmshousekeepingnoteondashboard AS ShowPMSHousekeepingNoteOnDashboards, pb2.pmshousekeepingnote AS PMSHousekeepingNote');

        }
        $result->leftJoin('t2.propertyid', 'p2')
            ->leftJoin('p2.regionid', 'r2')
            ->leftJoin('r2.regiongroupid', 'rgid')
            ->leftJoin('t2.propertybookingid', 'pb2')
            ->leftJoin('AppBundle:Propertybookings', 'npb2', Expr\Join::WITH, 't2.nextpropertybookingid=npb2.propertybookingid')
            ->leftJoin('AppBundle:PropertyStatuses', 'ps', Expr\Join::WITH, 'ps.propertystatusid=p2.propertystatusid')
            ->leftJoin('p2.customerid', 'c2')
            ->leftJoin('AppBundle:Taskstoservicers', 'ts', Expr\Join::WITH, 't2.taskid=ts.taskid')
            ->leftJoin('AppBundle:Servicers', 's2', Expr\Join::WITH, 'ts.servicerid=s2.servicerid')
            ->leftJoin('t2.propertyid', 'propertyid')
            ->leftJoin('AppBundle:Services', 'serviceid', Expr\Join::WITH, 't2.serviceid=serviceid.serviceid')
            ->leftJoin('AppBundle:Tasks', 'parenttask', Expr\Join::WITH, 'parenttask.taskid=t2.parenttaskid')
            ->leftJoin('AppBundle:Services', 'parentservice', Expr\Join::WITH, 'parenttask.serviceid=parentservice.serviceid')
            ->where('s2.servicerid='.$servicerID)
            ->andWhere('p2.active=1')
            ->andWhere('t2.active=1')
            ->andWhere('t2.completeconfirmeddate IS NULL')
            ->andWhere('p2.customerid=s2.customerid')
            ->andWhere('t2.taskdate >= c2.golivedate OR c2.golivedate IS NULL');

        $result->andWhere("t2.taskdate < :Today")
            ->setParameter('Today',$today);

        // Default Ordering
        if (!$taskID) {
            $result->addOrderBy('t2.taskdatetime')
                ->addOrderBy('r2.sortorder')
                ->addOrderBy('rgid.sortorder')
                ->addOrderBy('p2.sortorder')
                ->addOrderBy('t2.taskcompletebydate');

            // Conditional Ordering
            if ((int)$servicers[0]['SortQuickChangeToTop'] === 1) {
                $result->addOrderBy('t2.backtoback','DESC');

            } elseif ((int)$servicers[0]['SortQuickChangeToTop'] === 2) {
                $result->addOrderBy('t2.backtoback','DESC');
            }

            // If Task Estimates is true then select minimum and maximum time (In Hours)
            if($servicers[0]['ShowTaskEstimates']) {
                $result->addSelect('t2.mintimetocomplete AS Min, t2.maxtimetocomplete AS Max');
            }

            // Add Booking Colour details
            $result->addSelect('pb2.color AS BookingColor')
                ->addSelect('IDENTITY(pb2.propertyid) AS PropertyBookingPropertyID');

            // Fetch Guest Details based on conditions
            $result->addSelect('pb2.numberofguests AS PrevNumberOfGuests,pb2.numberofchildren AS PrevNumberOfChildren,pb2.numberofpets AS PrevNumberOfPets');
            $result->addSelect('npb2.numberofguests AS NextNumberOfGuests,npb2.numberofchildren AS NextNumberOfChildren,npb2.numberofpets AS NextNumberOfPets');

            $result->addSelect('pb2.guestemail AS PrevEmail,pb2.guestphone AS PrevPhone');
            $result->addSelect('npb2.guestemail AS NextEmail,npb2.guestphone AS NextPhone');

            $result->addSelect('pb2.guest AS PrevName');
            $result->addSelect('npb2.guest AS NextName');

            $result->distinct(true);
        } else {
            $result->andWhere('t2.taskid='.$taskID);
        }

        return $result->setMaxResults(31)->getQuery()
            ->getResult();
    }

    /**
     * @param $servicerID
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function FetchTasksForDashboard2($servicerID,$servicers,$taskID=null,$fields="*")
    {
        $viewTaskWithinDays = (int)$servicers[0]['ViewTaskWithinDays'];

        if ($viewTaskWithinDays === 0) {
            $viewTaskWithinDays = 7;
        }
        $min = min($viewTaskWithinDays,7);

        $now = (new \DateTime('now'));
        $now->setTimezone(new \DateTimeZone($servicers[0]['Region']));
        $today = $now->modify('+'.$min.' days')->format('Y-m-d');

        $query = "SELECT ".$fields."  FROM (".(new Tasks())->TasksQuery($servicerID,$servicers[0]['CustomerID']).") AS t where t.TaskDate < '".$today."'";
        if ($taskID) {
            $query .= " AND t.TaskID=".$taskID;
        }

        $result = $this->getEntityManager()->getConnection()->prepare($query);

        $result->execute();
        return $result->fetchAll();
    }

    /**
     * @param $taskID
     * @return mixed
     */
    public function GetTasksForInfoTab($taskID,$servicerID)
    {
        return $this->createQueryBuilder('t2')
            ->select('s2.servicerid,t2.taskid AS TaskID,c2.email AS Customers_Email,s2.email AS Servicers_Email,t2.taskstartdate AS TaskStartDate,t2.serviceid AS ServiceID,IDENTITY(t2.propertyid) AS PropertyID,p2.doorcode AS DoorCode,p2.propertyfile AS PropertyFile,p2.address AS Address,p2.description AS Description,p2.internalnotes AS InternalPropertyNotes, s2.timetracking AS TimeTracking, IDENTITY(s2.customerid) AS Servicers_CustomerID')
            ->leftJoin('t2.propertyid', 'p2')
            ->leftJoin('p2.customerid','c2')
            ->leftJoin('AppBundle:Taskstoservicers','ts',Expr\Join::WITH, 't2.taskid=ts.taskid')
            ->leftJoin('AppBundle:Servicers','s2',Expr\Join::WITH, 'ts.servicerid=s2.servicerid')
            ->where('t2.taskid=' . $taskID)
            ->andWhere('s2.servicerid='.$servicerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $taskID
     * @param $servicers
     * @return mixed
     */
    public function GetTasksForBookingTab($taskID, $servicers)
    {
        $result = $this->createQueryBuilder('t2')
            ->select('IDENTITY(t2.propertybookingid) AS PrevPropertyBookingID')
            ->addSelect('t2.nextpropertybookingid AS NextPropertyBookingID')
            ->addSelect('t2.internalnotes AS InternalNotes')
            ->addSelect('c2.quickchangeabbreviation AS QuickChangeAbbreviation')
            ->addSelect('c2.linenfields AS LinenFields')
            ->addSelect('pb2.linencounts AS PrevLinenCounts')
            ->addSelect('pb2.backtobackstart AS PrevBackToBackStart')
            ->addSelect('pb2.checkin AS PrevCheckIn')
            ->addSelect('pb2.checkintime AS PrevCheckInTime')
            ->addSelect('pb2.checkintimeminutes AS PrevCheckInTimeMinutes')
            ->addSelect('pb2.checkout AS PrevCheckOut')
            ->addSelect('pb2.checkouttime AS PrevCheckOutTime')
            ->addSelect('pb2.checkouttimeminutes AS PrevCheckOutTimeMinutes')
            ->addSelect('pb2.backtobackend AS PrevBackToBackEnd')
            ->addSelect('pb2.importbookingid AS PrevImportBookingID')
            ->addSelect('pb2.pmsnote AS PrevPMSHousekeepingNote')
            ->addSelect('pb2.globalnote AS PrevGlobalNote')
            ->addSelect('pb2.inglobalnote AS PrevInGlobalNote')
            ->addSelect('pb2.outglobalnote AS PrevOutGlobalNote')
            ->addSelect('pb2.ownernote AS PrevOwnerNote')
            ->addSelect('pb2.isowner AS PrevIsOwner')
            ->addSelect('npb2.linencounts AS NextLinenCounts')
            ->addSelect('npb2.isowner AS NextIsOwner')
            ->addSelect('npb2.backtobackstart AS NextBackToBackStart')
            ->addSelect('npb2.checkin AS NextCheckIn')
            ->addSelect('npb2.checkintime AS NextCheckInTime')
            ->addSelect('npb2.checkintimeminutes AS NextCheckInTimeMinutes')
            ->addSelect('npb2.checkout AS NextCheckOut')
            ->addSelect('npb2.checkouttime AS NextCheckOutTime')
            ->addSelect('npb2.checkouttimeminutes AS NextCheckOutTimeMinutes')
            ->addSelect('npb2.backtobackend AS NextBackToBackEnd')
            ->addSelect('npb2.importbookingid AS NextImportBookingID')
            ->addSelect('npb2.pmsnote AS NextPMSHousekeepingNote')
            ->addSelect('npb2.globalnote AS NextGlobalNote')
            ->addSelect('npb2.inglobalnote AS NextInGlobalNote')
            ->addSelect('npb2.outglobalnote AS NextOutGlobalNote')
            ->addSelect('npb2.ownernote AS NextOwnerNote')
        ;
        // Fetch Guest Details based on conditions
        if ($servicers[0]['IncludeGuestNumbers']) {
            $result->addSelect('pb2.numberofguests AS PrevGuestCount,pb2.numberofchildren AS PrevGuestChildren,pb2.numberofpets AS PrevGuestPets');
            $result->addSelect('npb2.numberofguests AS NextGuestCount,npb2.numberofchildren AS NextGuestChildren,npb2.numberofpets AS NextGuestPets');
        }

        if ($servicers[0]['IncludeGuestEmailPhone']) {
            $result->addSelect('pb2.guestemail AS PrevGuestEmail,pb2.guestphone AS PrevGuestPhone');
            $result->addSelect('npb2.guestemail AS NextGuestEmail,npb2.guestphone AS NextGuestPhone');
        }

        if ($servicers[0]['IncludeGuestName']) {
            $result->addSelect('pb2.guest AS PrevGuestName');
            $result->addSelect('npb2.guest AS NextGuestName');
        }
        $result->leftJoin('t2.propertyid', 'p2')
            ->leftJoin('p2.customerid','c2')
            ->leftJoin('t2.propertybookingid', 'pb2')
            ->leftJoin('AppBundle:Servicers', 's2', Expr\Join::WITH, 's2.servicerid=t2.servicerid')
            ->leftJoin('AppBundle:Propertybookings', 'npb2', Expr\Join::WITH, 't2.nextpropertybookingid=npb2.propertybookingid')
            ->andWhere('p2.active=1')
            ->andWhere('t2.active=1')
            ->andWhere('t2.taskid='.$taskID);
        return $result->getQuery()->execute();
    }

    /**
     * @param $propertyBookingID
     * @param null $limit
     * @return mixed
     */
    public function GetTasksForAssignmentsTab($propertyBookingID, $limit=null)
    {
        $result = $this->createQueryBuilder('t')
            ->select('s2.email AS ServicersEmail,s2.name AS ServicersName,s.abbreviation AS Abbreviation,s.servicename AS ServiceName,IDENTITY(ts.servicerid) AS ServicerID,t.completeconfirmeddate AS CompleteConfirmedDate,t.taskid AS TaskID,t.taskdate AS TaskDate')
            ->leftJoin('t.propertybookingid', 'pb')
            ->leftJoin('AppBundle:Taskstoservicers', 'ts', Expr\Join::WITH, 't.taskid = ts.taskid')
            ->leftJoin('AppBundle:Servicers', 's2', Expr\Join::WITH, 's2.servicerid=ts.servicerid')
            ->leftJoin('AppBundle:Services', 's', Expr\Join::WITH, 't.serviceid = s.serviceid')
            ->where('t.propertybookingid= :PropertyBookingID')
            ->setParameter('PropertyBookingID',$propertyBookingID)
            ->andWhere('t.tasktype <> 3')
            ->andWhere('t.propertybookingid IS NOT NULL OR t.propertybookingid <> 0')
            ->andWhere('t.active=1');

        if($limit) {
            $result->setMaxResults($limit);
        } else {
            $result->setMaxResults(500);
        }

        $result->orderBy('t.taskdate');

        return $result->getQuery()->execute();
    }

    /**
     * @param $taskID
     * @return mixed
     */
    public function GetTeamByTask($taskID,$servicers,$limit=null)
    {
        $viewTaskWithinDays = (int)$servicers[0]['ViewTaskWithinDays'];

        if ($viewTaskWithinDays === 0) {
            $viewTaskWithinDays = 7;
        }
        $min = min($viewTaskWithinDays,7);


        $now = (new \DateTime('now'));
        $now->setTimezone(new \DateTimeZone($servicers[0]['Region']));
        $today = $now->modify('+'.$min.' days')->format('Y-m-d');

        $result = $this->createQueryBuilder('t2')
            ->select('(CASE WHEN ts.islead=1 THEN 1 ELSE 0 END) AS IsLead,s2.name AS Name,s2.email AS Email,s2.phone AS Phone')
            ->leftJoin('t2.propertyid', 'p2')
            ->leftJoin('p2.customerid', 'c2')
            ->leftJoin('AppBundle:Taskstoservicers', 'ts', Expr\Join::WITH, 't2.taskid=ts.taskid')
            ->leftJoin('AppBundle:Servicers', 's2', Expr\Join::WITH, 'ts.servicerid=s2.servicerid')
            ->leftJoin('t2.propertyid', 'propertyid')
            ->leftJoin('AppBundle:Services', 'serviceid', Expr\Join::WITH, 't2.serviceid=serviceid.serviceid')
            ->andWhere('p2.active=1')
            ->andWhere('t2.active=1')
            ->andWhere('serviceid.active=1 OR serviceid.active IS NULL')
            ->andWhere('t2.completeconfirmeddate IS NULL')
            ->andWhere('t2.taskdate >= c2.golivedate OR c2.golivedate IS NULL')
            ->andWhere("t2.taskdate <= :Today")
            ->andWhere('t2.taskid=' . $taskID)
            ->setParameter('Today', $today)
            ->addOrderBy('t2.taskid', 'ASC')
            ->distinct(true)
            ->orderBy('s2.name', 'ASC');

        if($limit) {
            $result->setMaxResults($limit);
        }

        return $result->getQuery()
            ->execute();
    }

    /**
     * @param $taskID
     * @return mixed
     */
    public function GetCompleteConfirmedDateForStartTask($taskID)
    {
        return $this->createQueryBuilder('t')
            ->select('t.completeconfirmeddate')
            ->where('t.taskid='.$taskID)
            ->andWhere('t.completeconfirmeddate IS NULL')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $taskID
     * @param $servicerID
     * @return mixed
     */
    public function TaskToSaveManage($taskID, $servicerID)
    {
        return $this->createQueryBuilder('t')
            ->select('IDENTITY(t.propertyid) AS PropertyID,t.serviceid AS ServiceID,t.taskid AS TaskID')
            ->leftJoin('AppBundle:Taskstoservicers', 'ts', Expr\Join::WITH, 'ts.taskid=t.taskid')
            ->where('t.taskid='.$taskID)
            ->andWhere('ts.servicerid='.$servicerID)
            ->andWhere('t.completeconfirmeddate IS NULL')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $servicerID
     * @return mixed
     */
    public function AssignmentsTask($customerID,$limit,$propertyBookings=null,$properties=null)
    {
        $query = "SELECT TOP ".$limit." s0_.Email AS ServicersEmail, s0_.Name ServicersName, s0_.Phone AS ServicersPhone, t1_.PropertyID, t2_.IsLead,
                  t1_.TaskDate, t1_.PropertyBookingID, s3_.Abbreviation,
                  t1_.CompleteConfirmedDate, t2_.ServicerID, s3_.ServiceName,
                  t1_.TaskID FROM Tasks t1_ LEFT JOIN PropertyBookings p4_ ON t1_.PropertyBookingID = p4_.PropertyBookingID
                  LEFT JOIN Properties p5_ ON t1_.PropertyID = p5_.PropertyID LEFT JOIN Services s3_ ON (t1_.ServiceID = s3_.ServiceID)
                  LEFT JOIN TasksToServicers t2_ ON (t1_.TaskID = t2_.TaskID) LEFT JOIN Servicers s0_ ON (s0_.ServicerID = t2_.ServicerID)";
        $query .= " WHERE t1_.Active = 1 AND p5_.CustomerID = ".$customerID;

        if ($propertyBookings) {
            $query .= " AND (t1_.PropertyBookingID <> 0 OR t1_.PropertyBookingID <> '') AND t1_.PropertyBookingID IN
		          (".$propertyBookings.") AND t1_.TaskType <> 3";
        }

        if ($properties) {
            $query .= " AND t1_.PropertyID IN (".$properties.") and t1_.CompleteConfirmedDate IS NOT NULL 
            ORDER BY t1_.TaskDate DESC";
        }

        return $query;
    }

    /**
     * @param $taskIDs
     * @param $customerID
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function GetTaskServicers($taskIDs, $customerID,$servicers)
    {
        $viewTaskWithinDays = (int)$servicers[0]['ViewTaskWithinDays'];

        if ($viewTaskWithinDays === 0) {
            $viewTaskWithinDays = 7;
        }
        $min = min($viewTaskWithinDays,7);

        $now = (new \DateTime('now'));
        $now->setTimezone(new \DateTimeZone($servicers[0]['Region']));
        $today = $now->modify('+'.$min.' days')->format('Y-m-d');
        $query = "SELECT
              Distinct IsLead,Name,Tasks.TaskID,TimeZones.Region as TimeZoneRegion,Tasks.IncludeUrgentFlag,Tasks.PropertyID,StaffDashboardNote
              FROM Tasks 
              LEFT JOIN PropertyBookings ON Tasks.PropertyBookingID = PropertyBookings.PropertyBookingID
              LEFT JOIN Properties ON Tasks.PropertyID = Properties.PropertyID
              LEFT JOIN Customers ON Properties.CustomerID = Customers.CustomerID
              LEFT JOIN Regions ON Properties.RegionID = Regions.RegionID
              LEFT JOIN TimeZones ON Regions.TimeZoneID = TimeZones.TimeZoneID
              LEFT JOIN TasksToServicers ON Tasks.TaskID = TasksToServicers.TaskID
              LEFT JOIN Servicers ON TasksToServicers.ServicerID = Servicers.ServicerID
              LEFT JOIN Services ON Tasks.ServiceID = Services.ServiceID
        
              WHERE TAsks.Active = 1
              AND Properties.Active =1 
              anD Properties.CustomerID = " . $customerID . "
              AND Tasks.CompleteConfirmedDAte is NULL
              AND Tasks.TaskDAte <=   '" . $today . "' AND Tasks.TaskID IN (" . $taskIDs . ")";

        $query .= " AND (Services.Active = 1 OR Services.Active IS NULL)
              AND (Tasks.TaskDate >= Customers.GoLiveDAte or Customers.GoLiveDate is NULL) Order By Tasks.TaskID,Name
              ";
        $tasks = $this->getEntityManager()->getConnection()->prepare($query);
        $tasks->execute();
        return $tasks->fetchAll();
    }

    /**
     * @param $servicerID
     * @param $taskID
     * @return mixed
     */
    public function AcceptDeclineTask($servicerID, $taskID)
    {
        return $this->createQueryBuilder('t')
            ->select('timezoneid.region AS Region,t.taskdate AS TaskDate,t.taskname AS TaskName,s2.servicename AS ServiceName,p.propertyname AS PropertyName, t.taskid AS TaskID, IDENTITY(p.customerid) AS CustomerID')
            ->leftJoin('t.propertyid','p')
            ->leftJoin('AppBundle:Services','s2',Expr\Join::WITH, 't.serviceid=s2.serviceid')
            ->leftJoin('AppBundle:Taskstoservicers', 'ts', Expr\Join::WITH, 'ts.taskid=t.taskid')
            ->leftJoin('ts.servicerid','s')
            ->leftJoin('s.timezoneid','timezoneid')
            ->where('t.taskid='.$taskID)
            ->andWhere('ts.servicerid='.$servicerID)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $taskID
     * @param $servicerID
     * @return mixed
     */
    public function ChangeTaskDate($taskID, $servicerID)
    {
        return $this->createQueryBuilder('t')
            ->select('t.taskid AS TaskID')
            ->addSelect('t.taskdate AS TaskDate')
            ->addSelect('t.taskstartdate AS TaskStartDate')
            ->addSelect('t.taskstarttime AS TaskStartTime')
            ->addSelect('t.taskcompletebydate AS TaskCompleteByDate')
            ->addSelect('t.taskcompletebytime AS TaskCompleteByTime')
            ->addSelect('t.maxtimetocomplete AS MaxTimeToComplete')
            ->addSelect('s.schedulenote1 AS ScheduleNote1,s.schedulenote2 AS ScheduleNote2,s.schedulenote3 AS ScheduleNote3,s.schedulenote4 AS ScheduleNote4,s.schedulenote5 AS ScheduleNote5,s.schedulenote6 AS ScheduleNote6,s.schedulenote7 AS ScheduleNote7')
            ->addSelect('s.schedulenote1show AS Schedulenote1Show,s.schedulenote2show AS Schedulenote2Show,s.schedulenote3show AS Schedulenote3Show,s.schedulenote4show AS Schedulenote4Show,s.schedulenote5show AS Schedulenote5Show,s.schedulenote6show AS Schedulenote6Show,s.schedulenote7show AS Schedulenote7Show')
            ->leftJoin('AppBundle:Services','s2',Expr\Join::WITH, 't.serviceid=s2.serviceid')
            ->leftJoin('AppBundle:Taskstoservicers', 'ts', Expr\Join::WITH, 'ts.taskid=t.taskid')
            ->leftJoin('ts.servicerid','s')
            ->where('t.taskid='.$taskID)
            ->andWhere('ts.servicerid='.$servicerID)
            ->getQuery()
            ->execute();

    }

    /**
     * @param $servicerID
     * @param $taskID
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function SubmitManage($servicerID, $taskID)
    {
        $query = 'SELECT Tasks.TaskID,Properties.OwnerID,Properties.CustomerID,Tasks.NotifyOwnerOnCompletion,Tasks.NotifyCustomerOnCompletion,Properties.BeHome247ID,Services.BH247CleaningState,Services.BH247QAState,Services.BH247MaintenanceState,Services.BH247Custom_1State,Services.BH247Custom_2State,Tasks.ManagerServicerID,Tasks.PropertyID,Tasks.ServiceID,Tasks.IncludeToOwnerNote,Tasks.PropertyBookingID,Services.EscapiaHousekeepingStatus,Services.CloudbedsHousekeepingStatus,Services.MewsStatus,Services.OpertoStatus,Services.StreamlineHousekeepingStatus,Services.TrackHSCleanTypeID,Services.PropertyStatusID,Services.WebRezProStatus, Services.LMPMStatus, Services.GuestyStatus, Services.Beds24UnitStatusIndex,Services.Beds24UnitStatusIndex,Services.Beds24UnitStatusText FROM Tasks 
                  LEFT JOIN Properties ON Tasks.PropertyID = Properties.PropertyID
                  LEFT JOIN Services ON Tasks.ServiceID = Services.ServiceID
                  LEFT JOIN TasksToServicers ON Tasks.TaskID = TasksToServicers.TaskID
                  WHERE Tasks.TaskID = ' . $taskID . ' AND TasksToServicers.ServicerID = ' . $servicerID . ' and Tasks.CompleteConfirmedDate IS NULL';
        $tasks = $this->getEntityManager()->getConnection()->prepare($query);
        $tasks->execute();
        return $tasks->fetchAll();

    }

    /**
     * @param $servicerID
     * @param $propertyID
     * @return mixed
     */
    public function TaskSubmittedInLast5Seconds($servicerID, $propertyID)
    {
        $now = (new \DateTime('now'))->modify('-5 second');
        return $this->createQueryBuilder('t')
            ->select('t.taskid')
            ->where('t.propertyid='.$propertyID)
            ->andWhere('t.servicerid='.$servicerID)
            ->andWhere('t.createdate > :Last5Seconds')
            ->setParameter('Last5Seconds',$now)
            ->getQuery()
            ->execute();

    }

    /**
     * @param $servicerID
     * @param $taskID
     * @return mixed
     */
    public function DoesTaskBelongToServicer($servicerID, $taskID)
    {
        try {
            $result = $this->createQueryBuilder('t')
                ->select('t.taskid AS TaskID')
                ->leftJoin('AppBundle:Taskstoservicers', 'ts', Expr\Join::WITH, 'ts.taskid=t.taskid')
                ->where('t.taskid='.$taskID)
                ->andWhere('ts.servicerid='.$servicerID)
                ->getQuery()
                ->execute();

            if (empty($result)) {
                // Throw Error if the Task Does not belong to the Servicer.
                throw new BadRequestHttpException(ErrorConstants::WRONG_LOGIN);
            }
            return $result;
        } catch (BadRequestHttpException $exception) {
            throw $exception;
        }
    }

    /**
     * @param $servicerID
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function GetTasksForBookingCalender($servicerID, $startDate, $endDate,$timeZone)
    {
        $timeZone = new \DateTimeZone($timeZone);
        $startDateUTC = new \DateTime($startDate->format('Y-m-d H:i:s'));
        $startDateUTC = $startDateUTC->setTimezone($timeZone);
        $endDateUTC = new \DateTime($endDate->format('Y-m-d H:i:s'));
        $endDateUTC = $endDateUTC->setTimezone($timeZone);

        $query = "
        SELECT Distinct Lat,Lon,BookingConfirmedDate,ReminderConfirmedDate,TaskID,TaskDate,TaskTime,TaskCompleteByDate,TaskCompleteByTime,CompleteConfirmedDate,PropertyBookingID,CheckIn,CheckInTime,CheckOut,CheckOutTime,Source,PropertyBookingCreateDate,Guest,GuestEmail,GuestPhone,IsOwner,NumberOfGuests,NumberOfChildren,NumberOfPets,PropertyName,Region,Name,IsLead,ServiceName,TaskAbbreviation,Abbreviation,OwnerName,OwnerEmail,OwnerPhone,OwnerID,ServicerID,TaskType,PropertyID,REQUESTACCEPTANCEONASSIGNMENT,INCLUDEGUESTNAME,INCLUDEGUESTNUMBERS, TaskStartDate,TaskStartTime,TaskTimeMinutes,isnull(MinTimeTocomplete,0) as MinTimeToComplete,isnull(maxtimetocomplete,0) as MaxTimeToComplete,IncludeToOwnerNote,ClosedDAte,NeedsMaintenance,HasDamage,HasLostAndFound,SupplyFlag,DefaultToOwnerNote,OwnerSendEmails,OwnerSendTexts,BackToBackEnd,BackToBackStart,Active,Priority,TaskDateTime,NumberOfServicers,Address,PropertyAbbreviation,ServicerAbbreviation,IncludeServicerNote,IncludeDamage,IncludeMaintenance,IncludeLostAndFound,IncludeSupplyFlag,NotifyCustomerOnDamage,NotifyCustomerOnMaintenance,NotifyCustomerOnLostAndFound,NotifyCustomerOnServicerNote,NotifyOwnerOnCompletion,NotifyCustomerOnCompletion,CustomerID,TaskToServicerID,color,TaskName,PropertyFile,DoorCode,RegionSortOrder,RegionGroupSortORder,PropertySortOrder,RegionID,PropertyID,ServiceID,WorkDays,CreateDAte,PropertyBookingCreateDate,TaskImage1,TaskImage2,TaskImage3,LinkedPropertyID,LinkedVendor,LinkedCustomerID,AllowChangeTaskDate,AllowShareImagesWithOwners,AllowChangeTaskDate,Image1ShowOwner,Image2ShowOwner,Image3ShowOwner,GlobalNote,TaskDescription,InternalNotes,Description,ServicerNotes,InternalNote,ToOwnerNote,PiecePay,bookingcolor,ServiceColor,completedbyservicerid,PropertyBookingPropertyID,ScheduleVacantOnly,ParentTaskID,ParentServiceName,ParentStartDAte,ParentStartTIme,ParentMinTimeToComplete,ParentCompleteConfirmedDAte,ParentServiceAbbreviation,ParentTaskDAte,ShowTaskTimeEstimates,AcceptedDate,DeclinedDate,RequestAcceptTAsks,Instructions,InGlobalNote,OutGlobalNote,TimeZoneRegion,ClockIn,OnTheFlyTaskName,OwnerNote,ServiceGroup,ServiceGroupID,PropertiesCreateDate,Billable,PropertyItemTypeID,TimeZoneRegion,OneOffVacantOnly,TaskStartTimeMinutes,TaskCompleteByTimeMinutes,CheckInTimeMinutes,CheckOutTimeMinutes,Urgent,ShowTaskImage1OnOwnerReport,ShowTaskImage2OnOwnerReport,ShowTaskImage3OnOwnerReport,IncludeToOwnerNoteOnOwnerDashboard,ActiveForOwner,OwnerReportNote,TaskDescriptionImage2,TaskDescriptionImage1,TaskDescriptionImage3,CreatedByServicerID,DeactivatedByServicerID,Amount,ExpenseAmount,cast(dateadd(hour,timezone,CompleteConfirmedDate) as date) as CompleteConfirmedDateWithOffset FROM (".TaskWithServicers::vTasksWithServicers.") AS T WHERE T.ServicerID = ".$servicerID." AND (T.Active = 1 ) AND (T.TaskDateTime >= '".$startDate->format('Y-m-d H:i:s')."' OR T.CompleteConfirmedDate >= '".$startDateUTC->format('Y-m-d H:i:s')."') AND (T.TaskDateTime <= '".$endDate->format('Y-m-d H:i:s')."' OR T.CompleteConfirmedDate < '".$endDateUTC->format('Y-m-d H:i:s')."' ) ORDER BY T.Region,T.PropertyName,T.TaskDate,T.TaskID
        ";

        $result = $this->getEntityManager()->getConnection()->prepare($query);
        $result->execute();
        return $result->fetchAll();
    }

    /**
     * Function to validate if Task already exists with input request body.
     * @param $content
     * @return mixed
     */
    public function CheckValidTask($content)
    {
        return $this->createQueryBuilder('t')
            ->select('t.taskid')
            ->where('t.propertyid='.(int)$content[GeneralConstants::PROPERTY_ID])
            ->andWhere('t.serviceid='.(int)$content[GeneralConstants::TASKRULEID])
            ->andWhere('t.taskdescription = :TaskDescription')
            ->setParameter('TaskDescription',$content['TaskDescription'])
            ->andWhere('t.taskname = :TaskName')
            ->setParameter('TaskName',$content['TaskName'])
            ->andWhere('t.taskstartdate = :TaskStartDate')
            ->setParameter('TaskStartDate',$content['TaskStartDate'])
            ->andWhere('t.taskstarttime = '.(int)$content['TaskStartTime'])
            ->andWhere('t.taskcompletebydate = :TaskCompleteByDate')
            ->setParameter('TaskCompleteByDate',$content['TaskCompleteByDate'])
            ->andWhere('t.taskdate = :TaskDate')
            ->setParameter('TaskDate',$content['TaskDate'])
            ->andWhere('t.tasktime = '.(int)$content['TaskTime'])
            ->andWhere('t.taskcompletebytime = '.(int)$content['TaskCompleteByTime'])
            ->getQuery()
            ->execute();
    }
}