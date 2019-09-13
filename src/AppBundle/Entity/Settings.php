<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table(name="Settings")
 * @ORM\Entity
 */
class Settings
{
    /**
     * @var int
     *
     * @ORM\Column(name="SettingID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $settingid;

    /**
     * @var int
     *
     * @ORM\Column(name="LastImportedPropertyID", type="integer", nullable=false)
     */
    private $lastimportedpropertyid = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="LastImportedPropertyIDNEW", type="integer", nullable=false)
     */
    private $lastimportedpropertyidnew = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="LastImportedPropertyIntegrationID", type="integer", nullable=false)
     */
    private $lastimportedpropertyintegrationid = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="LastImportedPropertyIcalIntegrationID", type="integer", nullable=true)
     */
    private $lastimportedpropertyicalintegrationid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="LastGeneratedPropertyID", type="integer", nullable=true)
     */
    private $lastgeneratedpropertyid = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="DataImport", type="boolean", nullable=true)
     */
    private $dataimport;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DataImportCompletedDate", type="datetime", nullable=true)
     */
    private $dataimportcompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="iCALDataImport", type="boolean", nullable=true)
     */
    private $icaldataimport;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="iCALDataImportCompletedDate", type="datetime", nullable=true)
     */
    private $icaldataimportcompleteddate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="AllScriptsLastCompleted", type="datetime", nullable=true)
     */
    private $allscriptslastcompleted;

    /**
     * @var bool
     *
     * @ORM\Column(name="MinuteNotificationsCompleted", type="boolean", nullable=false)
     */
    private $minutenotificationscompleted = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="MinuteNotificationsCompletedDate", type="datetime", nullable=true)
     */
    private $minutenotificationscompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="BookingsImportServer1Completed", type="boolean", nullable=true)
     */
    private $bookingsimportserver1completed;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="BookingsImportServer1CompletedDate", type="datetime", nullable=true)
     */
    private $bookingsimportserver1completeddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="BookingsImportServer1NewCompleted", type="boolean", nullable=true)
     */
    private $bookingsimportserver1newcompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="BookingsImportServer1NewCompletedDate", type="datetime", nullable=true)
     */
    private $bookingsimportserver1newcompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="PropertyImportCompleted", type="boolean", nullable=true)
     */
    private $propertyimportcompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="PropertyImportCompletedDate", type="datetime", nullable=true)
     */
    private $propertyimportcompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="DailyTasksCompleted", type="boolean", nullable=true)
     */
    private $dailytaskscompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DailyTasksCompletedDate", type="datetime", nullable=true)
     */
    private $dailytaskscompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="DripDailyTasksCompleted", type="boolean", nullable=true)
     */
    private $dripdailytaskscompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DripDailyTasksCompletedDate", type="datetime", nullable=true)
     */
    private $dripdailytaskscompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="GeneratePropertyTasksCompleted", type="boolean", nullable=true)
     */
    private $generatepropertytaskscompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="GeneratePropertyTasksCompletedDate", type="datetime", nullable=true)
     */
    private $generatepropertytaskscompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="UpdateTimeZoneOffsetsComleted", type="boolean", nullable=true)
     */
    private $updatetimezoneoffsetscomleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="UpdateTimeZoneOffsetsCompletedDate", type="datetime", nullable=true)
     */
    private $updatetimezoneoffsetscompleteddate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="EscapiaAPIID", type="string", length=200, nullable=true)
     */
    private $escapiaapiid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="EscapiaAPIExpiration", type="datetime", nullable=true)
     */
    private $escapiaapiexpiration;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="BeHome247CheckedOutCompleted", type="boolean", nullable=true)
     */
    private $behome247checkedoutcompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="BeHome247CheckedOutCompletedDate", type="datetime", nullable=true)
     */
    private $behome247checkedoutcompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="OpertoCheckedOutCompleted", type="boolean", nullable=true)
     */
    private $opertocheckedoutcompleted = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="OpertoCheckedOutCompletedDate", type="datetime", nullable=true, options={"default"="getutcdate()"})
     */
    private $opertocheckedoutcompleteddate = 'getutcdate()';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="DeleteLogsServicer1Completed", type="boolean", nullable=true)
     */
    private $deletelogsservicer1completed;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DeleteLogsServicer1CompletedDate", type="datetime", nullable=true)
     */
    private $deletelogsservicer1completeddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="BookingsImportServer2Completed", type="boolean", nullable=true)
     */
    private $bookingsimportserver2completed;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="BookingsImportServer2CompletedDate", type="datetime", nullable=true)
     */
    private $bookingsimportserver2completeddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="DeleteLogsServicer2Completed", type="boolean", nullable=true)
     */
    private $deletelogsservicer2completed;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DeleteLogsServicer2CompletedDate", type="datetime", nullable=true)
     */
    private $deletelogsservicer2completeddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="PropertyCountsCompleted", type="boolean", nullable=true)
     */
    private $propertycountscompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="PropertyCountsCompletedDate", type="datetime", nullable=true)
     */
    private $propertycountscompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="StripeSyncBillingCompleted", type="boolean", nullable=true, options={"default"="1"})
     */
    private $stripesyncbillingcompleted = '1';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="StripeSyncBillingCompletedDate", type="datetime", nullable=true)
     */
    private $stripesyncbillingcompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AnnualAccountCreditsCompleted", type="boolean", nullable=true)
     */
    private $annualaccountcreditscompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="AnnualAccountCreditsCompletedDate", type="datetime", nullable=true)
     */
    private $annualaccountcreditscompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ScheduledWebhooksCompleted", type="boolean", nullable=true)
     */
    private $scheduledwebhookscompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ScheduledWebhooksCompletedDAte", type="datetime", nullable=true)
     */
    private $scheduledwebhookscompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ServiceReportGenerationCompleted", type="boolean", nullable=true, options={"default"="1"})
     */
    private $servicereportgenerationcompleted = '1';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ServiceReportGenerationCompletedDate", type="datetime", nullable=true)
     */
    private $servicereportgenerationcompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="PropertyReportGenerationCompleted", type="boolean", nullable=true, options={"default"="1"})
     */
    private $propertyreportgenerationcompleted = '1';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="PropertyReportGenerationCompletedDate", type="datetime", nullable=true)
     */
    private $propertyreportgenerationcompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="AnnualInvoicesCompleted", type="boolean", nullable=true)
     */
    private $annualinvoicescompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="AnnuaInvoicesCompletedDate", type="datetime", nullable=true)
     */
    private $annuainvoicescompleteddate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OpertoCardLast4", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $opertocardlast4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OpertoStripeID", type="string", length=200, nullable=true)
     */
    private $opertostripeid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OpertoStripeSourceID", type="string", length=200, nullable=true)
     */
    private $opertostripesourceid;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="WorkOrderProcessCompleted", type="boolean", nullable=true)
     */
    private $workorderprocesscompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="WorkOrderProcessCompletedDate", type="datetime", nullable=true)
     */
    private $workorderprocesscompleteddate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="OnceADayTasksCompleted", type="boolean", nullable=true)
     */
    private $onceadaytaskscompleted;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="OnceADayTasksCompletedDate", type="datetime", nullable=true)
     */
    private $onceadaytaskscompleteddate;



    /**
     * Get settingid.
     *
     * @return int
     */
    public function getSettingid()
    {
        return $this->settingid;
    }

    /**
     * Set lastimportedpropertyid.
     *
     * @param int $lastimportedpropertyid
     *
     * @return Settings
     */
    public function setLastimportedpropertyid($lastimportedpropertyid)
    {
        $this->lastimportedpropertyid = $lastimportedpropertyid;

        return $this;
    }

    /**
     * Get lastimportedpropertyid.
     *
     * @return int
     */
    public function getLastimportedpropertyid()
    {
        return $this->lastimportedpropertyid;
    }

    /**
     * Set lastimportedpropertyidnew.
     *
     * @param int $lastimportedpropertyidnew
     *
     * @return Settings
     */
    public function setLastimportedpropertyidnew($lastimportedpropertyidnew)
    {
        $this->lastimportedpropertyidnew = $lastimportedpropertyidnew;

        return $this;
    }

    /**
     * Get lastimportedpropertyidnew.
     *
     * @return int
     */
    public function getLastimportedpropertyidnew()
    {
        return $this->lastimportedpropertyidnew;
    }

    /**
     * Set lastimportedpropertyintegrationid.
     *
     * @param int $lastimportedpropertyintegrationid
     *
     * @return Settings
     */
    public function setLastimportedpropertyintegrationid($lastimportedpropertyintegrationid)
    {
        $this->lastimportedpropertyintegrationid = $lastimportedpropertyintegrationid;

        return $this;
    }

    /**
     * Get lastimportedpropertyintegrationid.
     *
     * @return int
     */
    public function getLastimportedpropertyintegrationid()
    {
        return $this->lastimportedpropertyintegrationid;
    }

    /**
     * Set lastimportedpropertyicalintegrationid.
     *
     * @param int|null $lastimportedpropertyicalintegrationid
     *
     * @return Settings
     */
    public function setLastimportedpropertyicalintegrationid($lastimportedpropertyicalintegrationid = null)
    {
        $this->lastimportedpropertyicalintegrationid = $lastimportedpropertyicalintegrationid;

        return $this;
    }

    /**
     * Get lastimportedpropertyicalintegrationid.
     *
     * @return int|null
     */
    public function getLastimportedpropertyicalintegrationid()
    {
        return $this->lastimportedpropertyicalintegrationid;
    }

    /**
     * Set lastgeneratedpropertyid.
     *
     * @param int|null $lastgeneratedpropertyid
     *
     * @return Settings
     */
    public function setLastgeneratedpropertyid($lastgeneratedpropertyid = null)
    {
        $this->lastgeneratedpropertyid = $lastgeneratedpropertyid;

        return $this;
    }

    /**
     * Get lastgeneratedpropertyid.
     *
     * @return int|null
     */
    public function getLastgeneratedpropertyid()
    {
        return $this->lastgeneratedpropertyid;
    }

    /**
     * Set dataimport.
     *
     * @param bool|null $dataimport
     *
     * @return Settings
     */
    public function setDataimport($dataimport = null)
    {
        $this->dataimport = $dataimport;

        return $this;
    }

    /**
     * Get dataimport.
     *
     * @return bool|null
     */
    public function getDataimport()
    {
        return $this->dataimport;
    }

    /**
     * Set dataimportcompleteddate.
     *
     * @param \DateTime|null $dataimportcompleteddate
     *
     * @return Settings
     */
    public function setDataimportcompleteddate($dataimportcompleteddate = null)
    {
        $this->dataimportcompleteddate = $dataimportcompleteddate;

        return $this;
    }

    /**
     * Get dataimportcompleteddate.
     *
     * @return \DateTime|null
     */
    public function getDataimportcompleteddate()
    {
        return $this->dataimportcompleteddate;
    }

    /**
     * Set icaldataimport.
     *
     * @param bool|null $icaldataimport
     *
     * @return Settings
     */
    public function setIcaldataimport($icaldataimport = null)
    {
        $this->icaldataimport = $icaldataimport;

        return $this;
    }

    /**
     * Get icaldataimport.
     *
     * @return bool|null
     */
    public function getIcaldataimport()
    {
        return $this->icaldataimport;
    }

    /**
     * Set icaldataimportcompleteddate.
     *
     * @param \DateTime|null $icaldataimportcompleteddate
     *
     * @return Settings
     */
    public function setIcaldataimportcompleteddate($icaldataimportcompleteddate = null)
    {
        $this->icaldataimportcompleteddate = $icaldataimportcompleteddate;

        return $this;
    }

    /**
     * Get icaldataimportcompleteddate.
     *
     * @return \DateTime|null
     */
    public function getIcaldataimportcompleteddate()
    {
        return $this->icaldataimportcompleteddate;
    }

    /**
     * Set allscriptslastcompleted.
     *
     * @param \DateTime|null $allscriptslastcompleted
     *
     * @return Settings
     */
    public function setAllscriptslastcompleted($allscriptslastcompleted = null)
    {
        $this->allscriptslastcompleted = $allscriptslastcompleted;

        return $this;
    }

    /**
     * Get allscriptslastcompleted.
     *
     * @return \DateTime|null
     */
    public function getAllscriptslastcompleted()
    {
        return $this->allscriptslastcompleted;
    }

    /**
     * Set minutenotificationscompleted.
     *
     * @param bool $minutenotificationscompleted
     *
     * @return Settings
     */
    public function setMinutenotificationscompleted($minutenotificationscompleted)
    {
        $this->minutenotificationscompleted = $minutenotificationscompleted;

        return $this;
    }

    /**
     * Get minutenotificationscompleted.
     *
     * @return bool
     */
    public function getMinutenotificationscompleted()
    {
        return $this->minutenotificationscompleted;
    }

    /**
     * Set minutenotificationscompleteddate.
     *
     * @param \DateTime|null $minutenotificationscompleteddate
     *
     * @return Settings
     */
    public function setMinutenotificationscompleteddate($minutenotificationscompleteddate = null)
    {
        $this->minutenotificationscompleteddate = $minutenotificationscompleteddate;

        return $this;
    }

    /**
     * Get minutenotificationscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getMinutenotificationscompleteddate()
    {
        return $this->minutenotificationscompleteddate;
    }

    /**
     * Set bookingsimportserver1completed.
     *
     * @param bool|null $bookingsimportserver1completed
     *
     * @return Settings
     */
    public function setBookingsimportserver1completed($bookingsimportserver1completed = null)
    {
        $this->bookingsimportserver1completed = $bookingsimportserver1completed;

        return $this;
    }

    /**
     * Get bookingsimportserver1completed.
     *
     * @return bool|null
     */
    public function getBookingsimportserver1completed()
    {
        return $this->bookingsimportserver1completed;
    }

    /**
     * Set bookingsimportserver1completeddate.
     *
     * @param \DateTime|null $bookingsimportserver1completeddate
     *
     * @return Settings
     */
    public function setBookingsimportserver1completeddate($bookingsimportserver1completeddate = null)
    {
        $this->bookingsimportserver1completeddate = $bookingsimportserver1completeddate;

        return $this;
    }

    /**
     * Get bookingsimportserver1completeddate.
     *
     * @return \DateTime|null
     */
    public function getBookingsimportserver1completeddate()
    {
        return $this->bookingsimportserver1completeddate;
    }

    /**
     * Set bookingsimportserver1newcompleted.
     *
     * @param bool|null $bookingsimportserver1newcompleted
     *
     * @return Settings
     */
    public function setBookingsimportserver1newcompleted($bookingsimportserver1newcompleted = null)
    {
        $this->bookingsimportserver1newcompleted = $bookingsimportserver1newcompleted;

        return $this;
    }

    /**
     * Get bookingsimportserver1newcompleted.
     *
     * @return bool|null
     */
    public function getBookingsimportserver1newcompleted()
    {
        return $this->bookingsimportserver1newcompleted;
    }

    /**
     * Set bookingsimportserver1newcompleteddate.
     *
     * @param \DateTime|null $bookingsimportserver1newcompleteddate
     *
     * @return Settings
     */
    public function setBookingsimportserver1newcompleteddate($bookingsimportserver1newcompleteddate = null)
    {
        $this->bookingsimportserver1newcompleteddate = $bookingsimportserver1newcompleteddate;

        return $this;
    }

    /**
     * Get bookingsimportserver1newcompleteddate.
     *
     * @return \DateTime|null
     */
    public function getBookingsimportserver1newcompleteddate()
    {
        return $this->bookingsimportserver1newcompleteddate;
    }

    /**
     * Set propertyimportcompleted.
     *
     * @param bool|null $propertyimportcompleted
     *
     * @return Settings
     */
    public function setPropertyimportcompleted($propertyimportcompleted = null)
    {
        $this->propertyimportcompleted = $propertyimportcompleted;

        return $this;
    }

    /**
     * Get propertyimportcompleted.
     *
     * @return bool|null
     */
    public function getPropertyimportcompleted()
    {
        return $this->propertyimportcompleted;
    }

    /**
     * Set propertyimportcompleteddate.
     *
     * @param \DateTime|null $propertyimportcompleteddate
     *
     * @return Settings
     */
    public function setPropertyimportcompleteddate($propertyimportcompleteddate = null)
    {
        $this->propertyimportcompleteddate = $propertyimportcompleteddate;

        return $this;
    }

    /**
     * Get propertyimportcompleteddate.
     *
     * @return \DateTime|null
     */
    public function getPropertyimportcompleteddate()
    {
        return $this->propertyimportcompleteddate;
    }

    /**
     * Set dailytaskscompleted.
     *
     * @param bool|null $dailytaskscompleted
     *
     * @return Settings
     */
    public function setDailytaskscompleted($dailytaskscompleted = null)
    {
        $this->dailytaskscompleted = $dailytaskscompleted;

        return $this;
    }

    /**
     * Get dailytaskscompleted.
     *
     * @return bool|null
     */
    public function getDailytaskscompleted()
    {
        return $this->dailytaskscompleted;
    }

    /**
     * Set dailytaskscompleteddate.
     *
     * @param \DateTime|null $dailytaskscompleteddate
     *
     * @return Settings
     */
    public function setDailytaskscompleteddate($dailytaskscompleteddate = null)
    {
        $this->dailytaskscompleteddate = $dailytaskscompleteddate;

        return $this;
    }

    /**
     * Get dailytaskscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getDailytaskscompleteddate()
    {
        return $this->dailytaskscompleteddate;
    }

    /**
     * Set dripdailytaskscompleted.
     *
     * @param bool|null $dripdailytaskscompleted
     *
     * @return Settings
     */
    public function setDripdailytaskscompleted($dripdailytaskscompleted = null)
    {
        $this->dripdailytaskscompleted = $dripdailytaskscompleted;

        return $this;
    }

    /**
     * Get dripdailytaskscompleted.
     *
     * @return bool|null
     */
    public function getDripdailytaskscompleted()
    {
        return $this->dripdailytaskscompleted;
    }

    /**
     * Set dripdailytaskscompleteddate.
     *
     * @param \DateTime|null $dripdailytaskscompleteddate
     *
     * @return Settings
     */
    public function setDripdailytaskscompleteddate($dripdailytaskscompleteddate = null)
    {
        $this->dripdailytaskscompleteddate = $dripdailytaskscompleteddate;

        return $this;
    }

    /**
     * Get dripdailytaskscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getDripdailytaskscompleteddate()
    {
        return $this->dripdailytaskscompleteddate;
    }

    /**
     * Set generatepropertytaskscompleted.
     *
     * @param bool|null $generatepropertytaskscompleted
     *
     * @return Settings
     */
    public function setGeneratepropertytaskscompleted($generatepropertytaskscompleted = null)
    {
        $this->generatepropertytaskscompleted = $generatepropertytaskscompleted;

        return $this;
    }

    /**
     * Get generatepropertytaskscompleted.
     *
     * @return bool|null
     */
    public function getGeneratepropertytaskscompleted()
    {
        return $this->generatepropertytaskscompleted;
    }

    /**
     * Set generatepropertytaskscompleteddate.
     *
     * @param \DateTime|null $generatepropertytaskscompleteddate
     *
     * @return Settings
     */
    public function setGeneratepropertytaskscompleteddate($generatepropertytaskscompleteddate = null)
    {
        $this->generatepropertytaskscompleteddate = $generatepropertytaskscompleteddate;

        return $this;
    }

    /**
     * Get generatepropertytaskscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getGeneratepropertytaskscompleteddate()
    {
        return $this->generatepropertytaskscompleteddate;
    }

    /**
     * Set updatetimezoneoffsetscomleted.
     *
     * @param bool|null $updatetimezoneoffsetscomleted
     *
     * @return Settings
     */
    public function setUpdatetimezoneoffsetscomleted($updatetimezoneoffsetscomleted = null)
    {
        $this->updatetimezoneoffsetscomleted = $updatetimezoneoffsetscomleted;

        return $this;
    }

    /**
     * Get updatetimezoneoffsetscomleted.
     *
     * @return bool|null
     */
    public function getUpdatetimezoneoffsetscomleted()
    {
        return $this->updatetimezoneoffsetscomleted;
    }

    /**
     * Set updatetimezoneoffsetscompleteddate.
     *
     * @param \DateTime|null $updatetimezoneoffsetscompleteddate
     *
     * @return Settings
     */
    public function setUpdatetimezoneoffsetscompleteddate($updatetimezoneoffsetscompleteddate = null)
    {
        $this->updatetimezoneoffsetscompleteddate = $updatetimezoneoffsetscompleteddate;

        return $this;
    }

    /**
     * Get updatetimezoneoffsetscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getUpdatetimezoneoffsetscompleteddate()
    {
        return $this->updatetimezoneoffsetscompleteddate;
    }

    /**
     * Set escapiaapiid.
     *
     * @param string|null $escapiaapiid
     *
     * @return Settings
     */
    public function setEscapiaapiid($escapiaapiid = null)
    {
        $this->escapiaapiid = $escapiaapiid;

        return $this;
    }

    /**
     * Get escapiaapiid.
     *
     * @return string|null
     */
    public function getEscapiaapiid()
    {
        return $this->escapiaapiid;
    }

    /**
     * Set escapiaapiexpiration.
     *
     * @param \DateTime|null $escapiaapiexpiration
     *
     * @return Settings
     */
    public function setEscapiaapiexpiration($escapiaapiexpiration = null)
    {
        $this->escapiaapiexpiration = $escapiaapiexpiration;

        return $this;
    }

    /**
     * Get escapiaapiexpiration.
     *
     * @return \DateTime|null
     */
    public function getEscapiaapiexpiration()
    {
        return $this->escapiaapiexpiration;
    }

    /**
     * Set behome247checkedoutcompleted.
     *
     * @param bool|null $behome247checkedoutcompleted
     *
     * @return Settings
     */
    public function setBehome247checkedoutcompleted($behome247checkedoutcompleted = null)
    {
        $this->behome247checkedoutcompleted = $behome247checkedoutcompleted;

        return $this;
    }

    /**
     * Get behome247checkedoutcompleted.
     *
     * @return bool|null
     */
    public function getBehome247checkedoutcompleted()
    {
        return $this->behome247checkedoutcompleted;
    }

    /**
     * Set behome247checkedoutcompleteddate.
     *
     * @param \DateTime|null $behome247checkedoutcompleteddate
     *
     * @return Settings
     */
    public function setBehome247checkedoutcompleteddate($behome247checkedoutcompleteddate = null)
    {
        $this->behome247checkedoutcompleteddate = $behome247checkedoutcompleteddate;

        return $this;
    }

    /**
     * Get behome247checkedoutcompleteddate.
     *
     * @return \DateTime|null
     */
    public function getBehome247checkedoutcompleteddate()
    {
        return $this->behome247checkedoutcompleteddate;
    }

    /**
     * Set opertocheckedoutcompleted.
     *
     * @param bool|null $opertocheckedoutcompleted
     *
     * @return Settings
     */
    public function setOpertocheckedoutcompleted($opertocheckedoutcompleted = null)
    {
        $this->opertocheckedoutcompleted = $opertocheckedoutcompleted;

        return $this;
    }

    /**
     * Get opertocheckedoutcompleted.
     *
     * @return bool|null
     */
    public function getOpertocheckedoutcompleted()
    {
        return $this->opertocheckedoutcompleted;
    }

    /**
     * Set opertocheckedoutcompleteddate.
     *
     * @param \DateTime|null $opertocheckedoutcompleteddate
     *
     * @return Settings
     */
    public function setOpertocheckedoutcompleteddate($opertocheckedoutcompleteddate = null)
    {
        $this->opertocheckedoutcompleteddate = $opertocheckedoutcompleteddate;

        return $this;
    }

    /**
     * Get opertocheckedoutcompleteddate.
     *
     * @return \DateTime|null
     */
    public function getOpertocheckedoutcompleteddate()
    {
        return $this->opertocheckedoutcompleteddate;
    }

    /**
     * Set deletelogsservicer1completed.
     *
     * @param bool|null $deletelogsservicer1completed
     *
     * @return Settings
     */
    public function setDeletelogsservicer1completed($deletelogsservicer1completed = null)
    {
        $this->deletelogsservicer1completed = $deletelogsservicer1completed;

        return $this;
    }

    /**
     * Get deletelogsservicer1completed.
     *
     * @return bool|null
     */
    public function getDeletelogsservicer1completed()
    {
        return $this->deletelogsservicer1completed;
    }

    /**
     * Set deletelogsservicer1completeddate.
     *
     * @param \DateTime|null $deletelogsservicer1completeddate
     *
     * @return Settings
     */
    public function setDeletelogsservicer1completeddate($deletelogsservicer1completeddate = null)
    {
        $this->deletelogsservicer1completeddate = $deletelogsservicer1completeddate;

        return $this;
    }

    /**
     * Get deletelogsservicer1completeddate.
     *
     * @return \DateTime|null
     */
    public function getDeletelogsservicer1completeddate()
    {
        return $this->deletelogsservicer1completeddate;
    }

    /**
     * Set bookingsimportserver2completed.
     *
     * @param bool|null $bookingsimportserver2completed
     *
     * @return Settings
     */
    public function setBookingsimportserver2completed($bookingsimportserver2completed = null)
    {
        $this->bookingsimportserver2completed = $bookingsimportserver2completed;

        return $this;
    }

    /**
     * Get bookingsimportserver2completed.
     *
     * @return bool|null
     */
    public function getBookingsimportserver2completed()
    {
        return $this->bookingsimportserver2completed;
    }

    /**
     * Set bookingsimportserver2completeddate.
     *
     * @param \DateTime|null $bookingsimportserver2completeddate
     *
     * @return Settings
     */
    public function setBookingsimportserver2completeddate($bookingsimportserver2completeddate = null)
    {
        $this->bookingsimportserver2completeddate = $bookingsimportserver2completeddate;

        return $this;
    }

    /**
     * Get bookingsimportserver2completeddate.
     *
     * @return \DateTime|null
     */
    public function getBookingsimportserver2completeddate()
    {
        return $this->bookingsimportserver2completeddate;
    }

    /**
     * Set deletelogsservicer2completed.
     *
     * @param bool|null $deletelogsservicer2completed
     *
     * @return Settings
     */
    public function setDeletelogsservicer2completed($deletelogsservicer2completed = null)
    {
        $this->deletelogsservicer2completed = $deletelogsservicer2completed;

        return $this;
    }

    /**
     * Get deletelogsservicer2completed.
     *
     * @return bool|null
     */
    public function getDeletelogsservicer2completed()
    {
        return $this->deletelogsservicer2completed;
    }

    /**
     * Set deletelogsservicer2completeddate.
     *
     * @param \DateTime|null $deletelogsservicer2completeddate
     *
     * @return Settings
     */
    public function setDeletelogsservicer2completeddate($deletelogsservicer2completeddate = null)
    {
        $this->deletelogsservicer2completeddate = $deletelogsservicer2completeddate;

        return $this;
    }

    /**
     * Get deletelogsservicer2completeddate.
     *
     * @return \DateTime|null
     */
    public function getDeletelogsservicer2completeddate()
    {
        return $this->deletelogsservicer2completeddate;
    }

    /**
     * Set propertycountscompleted.
     *
     * @param bool|null $propertycountscompleted
     *
     * @return Settings
     */
    public function setPropertycountscompleted($propertycountscompleted = null)
    {
        $this->propertycountscompleted = $propertycountscompleted;

        return $this;
    }

    /**
     * Get propertycountscompleted.
     *
     * @return bool|null
     */
    public function getPropertycountscompleted()
    {
        return $this->propertycountscompleted;
    }

    /**
     * Set propertycountscompleteddate.
     *
     * @param \DateTime|null $propertycountscompleteddate
     *
     * @return Settings
     */
    public function setPropertycountscompleteddate($propertycountscompleteddate = null)
    {
        $this->propertycountscompleteddate = $propertycountscompleteddate;

        return $this;
    }

    /**
     * Get propertycountscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getPropertycountscompleteddate()
    {
        return $this->propertycountscompleteddate;
    }

    /**
     * Set stripesyncbillingcompleted.
     *
     * @param bool|null $stripesyncbillingcompleted
     *
     * @return Settings
     */
    public function setStripesyncbillingcompleted($stripesyncbillingcompleted = null)
    {
        $this->stripesyncbillingcompleted = $stripesyncbillingcompleted;

        return $this;
    }

    /**
     * Get stripesyncbillingcompleted.
     *
     * @return bool|null
     */
    public function getStripesyncbillingcompleted()
    {
        return $this->stripesyncbillingcompleted;
    }

    /**
     * Set stripesyncbillingcompleteddate.
     *
     * @param \DateTime|null $stripesyncbillingcompleteddate
     *
     * @return Settings
     */
    public function setStripesyncbillingcompleteddate($stripesyncbillingcompleteddate = null)
    {
        $this->stripesyncbillingcompleteddate = $stripesyncbillingcompleteddate;

        return $this;
    }

    /**
     * Get stripesyncbillingcompleteddate.
     *
     * @return \DateTime|null
     */
    public function getStripesyncbillingcompleteddate()
    {
        return $this->stripesyncbillingcompleteddate;
    }

    /**
     * Set annualaccountcreditscompleted.
     *
     * @param bool|null $annualaccountcreditscompleted
     *
     * @return Settings
     */
    public function setAnnualaccountcreditscompleted($annualaccountcreditscompleted = null)
    {
        $this->annualaccountcreditscompleted = $annualaccountcreditscompleted;

        return $this;
    }

    /**
     * Get annualaccountcreditscompleted.
     *
     * @return bool|null
     */
    public function getAnnualaccountcreditscompleted()
    {
        return $this->annualaccountcreditscompleted;
    }

    /**
     * Set annualaccountcreditscompleteddate.
     *
     * @param \DateTime|null $annualaccountcreditscompleteddate
     *
     * @return Settings
     */
    public function setAnnualaccountcreditscompleteddate($annualaccountcreditscompleteddate = null)
    {
        $this->annualaccountcreditscompleteddate = $annualaccountcreditscompleteddate;

        return $this;
    }

    /**
     * Get annualaccountcreditscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getAnnualaccountcreditscompleteddate()
    {
        return $this->annualaccountcreditscompleteddate;
    }

    /**
     * Set scheduledwebhookscompleted.
     *
     * @param bool|null $scheduledwebhookscompleted
     *
     * @return Settings
     */
    public function setScheduledwebhookscompleted($scheduledwebhookscompleted = null)
    {
        $this->scheduledwebhookscompleted = $scheduledwebhookscompleted;

        return $this;
    }

    /**
     * Get scheduledwebhookscompleted.
     *
     * @return bool|null
     */
    public function getScheduledwebhookscompleted()
    {
        return $this->scheduledwebhookscompleted;
    }

    /**
     * Set scheduledwebhookscompleteddate.
     *
     * @param \DateTime|null $scheduledwebhookscompleteddate
     *
     * @return Settings
     */
    public function setScheduledwebhookscompleteddate($scheduledwebhookscompleteddate = null)
    {
        $this->scheduledwebhookscompleteddate = $scheduledwebhookscompleteddate;

        return $this;
    }

    /**
     * Get scheduledwebhookscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getScheduledwebhookscompleteddate()
    {
        return $this->scheduledwebhookscompleteddate;
    }

    /**
     * Set servicereportgenerationcompleted.
     *
     * @param bool|null $servicereportgenerationcompleted
     *
     * @return Settings
     */
    public function setServicereportgenerationcompleted($servicereportgenerationcompleted = null)
    {
        $this->servicereportgenerationcompleted = $servicereportgenerationcompleted;

        return $this;
    }

    /**
     * Get servicereportgenerationcompleted.
     *
     * @return bool|null
     */
    public function getServicereportgenerationcompleted()
    {
        return $this->servicereportgenerationcompleted;
    }

    /**
     * Set servicereportgenerationcompleteddate.
     *
     * @param \DateTime|null $servicereportgenerationcompleteddate
     *
     * @return Settings
     */
    public function setServicereportgenerationcompleteddate($servicereportgenerationcompleteddate = null)
    {
        $this->servicereportgenerationcompleteddate = $servicereportgenerationcompleteddate;

        return $this;
    }

    /**
     * Get servicereportgenerationcompleteddate.
     *
     * @return \DateTime|null
     */
    public function getServicereportgenerationcompleteddate()
    {
        return $this->servicereportgenerationcompleteddate;
    }

    /**
     * Set propertyreportgenerationcompleted.
     *
     * @param bool|null $propertyreportgenerationcompleted
     *
     * @return Settings
     */
    public function setPropertyreportgenerationcompleted($propertyreportgenerationcompleted = null)
    {
        $this->propertyreportgenerationcompleted = $propertyreportgenerationcompleted;

        return $this;
    }

    /**
     * Get propertyreportgenerationcompleted.
     *
     * @return bool|null
     */
    public function getPropertyreportgenerationcompleted()
    {
        return $this->propertyreportgenerationcompleted;
    }

    /**
     * Set propertyreportgenerationcompleteddate.
     *
     * @param \DateTime|null $propertyreportgenerationcompleteddate
     *
     * @return Settings
     */
    public function setPropertyreportgenerationcompleteddate($propertyreportgenerationcompleteddate = null)
    {
        $this->propertyreportgenerationcompleteddate = $propertyreportgenerationcompleteddate;

        return $this;
    }

    /**
     * Get propertyreportgenerationcompleteddate.
     *
     * @return \DateTime|null
     */
    public function getPropertyreportgenerationcompleteddate()
    {
        return $this->propertyreportgenerationcompleteddate;
    }

    /**
     * Set annualinvoicescompleted.
     *
     * @param bool|null $annualinvoicescompleted
     *
     * @return Settings
     */
    public function setAnnualinvoicescompleted($annualinvoicescompleted = null)
    {
        $this->annualinvoicescompleted = $annualinvoicescompleted;

        return $this;
    }

    /**
     * Get annualinvoicescompleted.
     *
     * @return bool|null
     */
    public function getAnnualinvoicescompleted()
    {
        return $this->annualinvoicescompleted;
    }

    /**
     * Set annuainvoicescompleteddate.
     *
     * @param \DateTime|null $annuainvoicescompleteddate
     *
     * @return Settings
     */
    public function setAnnuainvoicescompleteddate($annuainvoicescompleteddate = null)
    {
        $this->annuainvoicescompleteddate = $annuainvoicescompleteddate;

        return $this;
    }

    /**
     * Get annuainvoicescompleteddate.
     *
     * @return \DateTime|null
     */
    public function getAnnuainvoicescompleteddate()
    {
        return $this->annuainvoicescompleteddate;
    }

    /**
     * Set opertocardlast4.
     *
     * @param string|null $opertocardlast4
     *
     * @return Settings
     */
    public function setOpertocardlast4($opertocardlast4 = null)
    {
        $this->opertocardlast4 = $opertocardlast4;

        return $this;
    }

    /**
     * Get opertocardlast4.
     *
     * @return string|null
     */
    public function getOpertocardlast4()
    {
        return $this->opertocardlast4;
    }

    /**
     * Set opertostripeid.
     *
     * @param string|null $opertostripeid
     *
     * @return Settings
     */
    public function setOpertostripeid($opertostripeid = null)
    {
        $this->opertostripeid = $opertostripeid;

        return $this;
    }

    /**
     * Get opertostripeid.
     *
     * @return string|null
     */
    public function getOpertostripeid()
    {
        return $this->opertostripeid;
    }

    /**
     * Set opertostripesourceid.
     *
     * @param string|null $opertostripesourceid
     *
     * @return Settings
     */
    public function setOpertostripesourceid($opertostripesourceid = null)
    {
        $this->opertostripesourceid = $opertostripesourceid;

        return $this;
    }

    /**
     * Get opertostripesourceid.
     *
     * @return string|null
     */
    public function getOpertostripesourceid()
    {
        return $this->opertostripesourceid;
    }

    /**
     * Set workorderprocesscompleted.
     *
     * @param bool|null $workorderprocesscompleted
     *
     * @return Settings
     */
    public function setWorkorderprocesscompleted($workorderprocesscompleted = null)
    {
        $this->workorderprocesscompleted = $workorderprocesscompleted;

        return $this;
    }

    /**
     * Get workorderprocesscompleted.
     *
     * @return bool|null
     */
    public function getWorkorderprocesscompleted()
    {
        return $this->workorderprocesscompleted;
    }

    /**
     * Set workorderprocesscompleteddate.
     *
     * @param \DateTime|null $workorderprocesscompleteddate
     *
     * @return Settings
     */
    public function setWorkorderprocesscompleteddate($workorderprocesscompleteddate = null)
    {
        $this->workorderprocesscompleteddate = $workorderprocesscompleteddate;

        return $this;
    }

    /**
     * Get workorderprocesscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getWorkorderprocesscompleteddate()
    {
        return $this->workorderprocesscompleteddate;
    }

    /**
     * Set onceadaytaskscompleted.
     *
     * @param bool|null $onceadaytaskscompleted
     *
     * @return Settings
     */
    public function setOnceadaytaskscompleted($onceadaytaskscompleted = null)
    {
        $this->onceadaytaskscompleted = $onceadaytaskscompleted;

        return $this;
    }

    /**
     * Get onceadaytaskscompleted.
     *
     * @return bool|null
     */
    public function getOnceadaytaskscompleted()
    {
        return $this->onceadaytaskscompleted;
    }

    /**
     * Set onceadaytaskscompleteddate.
     *
     * @param \DateTime|null $onceadaytaskscompleteddate
     *
     * @return Settings
     */
    public function setOnceadaytaskscompleteddate($onceadaytaskscompleteddate = null)
    {
        $this->onceadaytaskscompleteddate = $onceadaytaskscompleteddate;

        return $this;
    }

    /**
     * Get onceadaytaskscompleteddate.
     *
     * @return \DateTime|null
     */
    public function getOnceadaytaskscompleteddate()
    {
        return $this->onceadaytaskscompleteddate;
    }
}
