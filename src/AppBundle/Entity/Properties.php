<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Properties
 *
 * @ORM\Table(name="Properties", indexes={@ORM\Index(name="active", columns={"Active"}), @ORM\Index(name="CustomerID", columns={"CustomerID"}), @ORM\Index(name="LinkedPropertyID", columns={"LinkedPropertyID"}), @ORM\Index(name="OwnerID", columns={"OwnerID"}), @ORM\Index(name="Performing Import", columns={"PerformingImport"}), @ORM\Index(name="Performing Import Date", columns={"PerformingImportDate"}), @ORM\Index(name="PropertyName", columns={"PropertyName"}), @ORM\Index(name="RegionID", columns={"RegionID"}), @ORM\Index(name="sortorder", columns={"SortOrder"})})
 * @ORM\Entity
 */
class Properties
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertyid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="IntegrationCompanyOwnerID", type="integer", nullable=true)
     */
    private $integrationcompanyownerid;

    /**
     * @var string
     *
     * @ORM\Column(name="UUID", type="guid", nullable=false, options={"default"="newid()"})
     */
    private $uuid = 'newid()';

    /**
     * @var bool
     *
     * @ORM\Column(name="OpertoFlag", type="boolean", nullable=false)
     */
    private $opertoflag = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="BeHome247ID", type="integer", nullable=true)
     */
    private $behome247id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PointCentralID", type="integer", nullable=true)
     */
    private $pointcentralid;

    /**
     * @var bool
     *
     * @ORM\Column(name="PointCentralConnected", type="boolean", nullable=false)
     */
    private $pointcentralconnected = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="PointCentralMessage", type="string", length=50, nullable=true)
     */
    private $pointcentralmessage;

    /**
     * @var int|null
     *
     * @ORM\Column(name="LinkedPropertyID", type="integer", nullable=true)
     */
    private $linkedpropertyid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="LinkedPropertyCode", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $linkedpropertycode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCalLink", type="string", length=255, nullable=true)
     */
    private $icallink;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCalLinkStatus", type="string", length=2000, nullable=true)
     */
    private $icallinkstatus;

    /**
     * @var int|null
     *
     * @ORM\Column(name="iCalLinkTry", type="integer", nullable=true)
     */
    private $icallinktry;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ImportBlocks", type="boolean", nullable=true)
     */
    private $importblocks = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCalLink4", type="string", length=255, nullable=true)
     */
    private $icallink4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCalLink4Status", type="string", length=2000, nullable=true)
     */
    private $icallink4status;

    /**
     * @var int|null
     *
     * @ORM\Column(name="iCalLinkTry4", type="integer", nullable=true)
     */
    private $icallinktry4 = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ImportBlocks4", type="boolean", nullable=true)
     */
    private $importblocks4 = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCalLink3", type="string", length=255, nullable=true)
     */
    private $icallink3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCalLink3Status", type="string", length=2000, nullable=true)
     */
    private $icallink3status;

    /**
     * @var int|null
     *
     * @ORM\Column(name="iCalLinkTry3", type="integer", nullable=true)
     */
    private $icallinktry3 = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ImportBlocks3", type="boolean", nullable=true)
     */
    private $importblocks3 = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCalLink2", type="string", length=255, nullable=true)
     */
    private $icallink2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCalLink2Status", type="string", length=2000, nullable=true)
     */
    private $icallink2status;

    /**
     * @var int|null
     *
     * @ORM\Column(name="iCalLinkTry2", type="integer", nullable=true)
     */
    private $icallinktry2 = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ImportBlocks2", type="boolean", nullable=true)
     */
    private $importblocks2 = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="JSON", type="string", length=255, nullable=true)
     */
    private $json;

    /**
     * @var string|null
     *
     * @ORM\Column(name="JSONStatus", type="string", length=2000, nullable=true)
     */
    private $jsonstatus;

    /**
     * @var int|null
     *
     * @ORM\Column(name="JsonTry", type="integer", nullable=true)
     */
    private $jsontry = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="PerformingImport", type="boolean", nullable=false)
     */
    private $performingimport = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="PerformingImportDate", type="datetime", nullable=true)
     */
    private $performingimportdate;

    /**
     * @var int
     *
     * @ORM\Column(name="ImportIssueCount", type="integer", nullable=false)
     */
    private $importissuecount = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="ImportIssueNote", type="text", length=16, nullable=true)
     */
    private $importissuenote;

    /**
     * @var string
     *
     * @ORM\Column(name="PropertyName", type="string", length=100, nullable=false)
     */
    private $propertyname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PropertyAbbreviation", type="string", length=6, nullable=true)
     */
    private $propertyabbreviation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BookingSoftwareName", type="string", length=0, nullable=true)
     */
    private $bookingsoftwarename;

    /**
     * @var int
     *
     * @ORM\Column(name="DefaultCheckInTime", type="integer", nullable=false)
     */
    private $defaultcheckintime;

    /**
     * @var int
     *
     * @ORM\Column(name="DefaultCheckInTimeMinutes", type="integer", nullable=false)
     */
    private $defaultcheckintimeminutes = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="DefaultCheckOutTime", type="integer", nullable=false)
     */
    private $defaultcheckouttime;

    /**
     * @var int
     *
     * @ORM\Column(name="DefaultCheckOutTimeMinutes", type="integer", nullable=false)
     */
    private $defaultcheckouttimeminutes = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Address", type="string", length=1000, nullable=true)
     */
    private $address;

    /**
     * @var float|null
     *
     * @ORM\Column(name="lat", type="float", precision=53, scale=0, nullable=true)
     */
    private $lat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="lon", type="float", precision=53, scale=0, nullable=true)
     */
    private $lon;

    /**
     * @var bool
     *
     * @ORM\Column(name="CantFindLatLon", type="boolean", nullable=false)
     */
    private $cantfindlatlon = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="PropertyNotes", type="string", length=0, nullable=true)
     */
    private $propertynotes;

    /**
     * @var string|null
     *
     * @ORM\Column(name="DoorCode", type="string", length=50, nullable=true)
     */
    private $doorcode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image1", type="string", length=1000, nullable=true)
     */
    private $image1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image2", type="string", length=1000, nullable=true)
     */
    private $image2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PropertyFile", type="string", length=1000, nullable=true)
     */
    private $propertyfile;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=0, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="InternalNotes", type="string", length=0, nullable=true)
     */
    private $internalnotes;

    /**
     * @var bool
     *
     * @ORM\Column(name="OnboardingBookingsChecked", type="boolean", nullable=false)
     */
    private $onboardingbookingschecked = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="HasNoBookings", type="boolean", nullable=false)
     */
    private $hasnobookings = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="BookingImportLinksUnavailable", type="boolean", nullable=false)
     */
    private $bookingimportlinksunavailable = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="OnboardingOwnerVerified", type="boolean", nullable=false)
     */
    private $onboardingownerverified = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="SetupComplete", type="boolean", nullable=false)
     */
    private $setupcomplete = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="AccountName", type="string", length=200, nullable=true)
     */
    private $accountname;

    /**
     * @var int
     *
     * @ORM\Column(name="ImportCount", type="integer", nullable=false)
     */
    private $importcount = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdate = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DeleteDate", type="datetime", nullable=true)
     */
    private $deletedate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="UpdateDate", type="datetime", nullable=true)
     */
    private $updatedate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="OpertoStartDate", type="datetime", nullable=true)
     */
    private $opertostartdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="OpertoEndDate", type="datetime", nullable=true)
     */
    private $opertoenddate;

    /**
     * @var \Customers
     *
     * @ORM\ManyToOne(targetEntity="Customers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CustomerID", referencedColumnName="CustomerID")
     * })
     */
    private $customerid;

    /**
     * @var \Owners
     *
     * @ORM\ManyToOne(targetEntity="Owners")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="OwnerID", referencedColumnName="OwnerID")
     * })
     */
    private $ownerid;

    /**
     * @var \Regions
     *
     * @ORM\ManyToOne(targetEntity="Regions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RegionID", referencedColumnName="RegionID")
     * })
     */
    private $regionid;



    /**
     * Get propertyid.
     *
     * @return int
     */
    public function getPropertyid()
    {
        return $this->propertyid;
    }

    /**
     * Set integrationcompanyownerid.
     *
     * @param int|null $integrationcompanyownerid
     *
     * @return Properties
     */
    public function setIntegrationcompanyownerid($integrationcompanyownerid = null)
    {
        $this->integrationcompanyownerid = $integrationcompanyownerid;

        return $this;
    }

    /**
     * Get integrationcompanyownerid.
     *
     * @return int|null
     */
    public function getIntegrationcompanyownerid()
    {
        return $this->integrationcompanyownerid;
    }

    /**
     * Set uuid.
     *
     * @param string $uuid
     *
     * @return Properties
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid.
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set opertoflag.
     *
     * @param bool $opertoflag
     *
     * @return Properties
     */
    public function setOpertoflag($opertoflag)
    {
        $this->opertoflag = $opertoflag;

        return $this;
    }

    /**
     * Get opertoflag.
     *
     * @return bool
     */
    public function getOpertoflag()
    {
        return $this->opertoflag;
    }

    /**
     * Set behome247id.
     *
     * @param int|null $behome247id
     *
     * @return Properties
     */
    public function setBehome247id($behome247id = null)
    {
        $this->behome247id = $behome247id;

        return $this;
    }

    /**
     * Get behome247id.
     *
     * @return int|null
     */
    public function getBehome247id()
    {
        return $this->behome247id;
    }

    /**
     * Set pointcentralid.
     *
     * @param int|null $pointcentralid
     *
     * @return Properties
     */
    public function setPointcentralid($pointcentralid = null)
    {
        $this->pointcentralid = $pointcentralid;

        return $this;
    }

    /**
     * Get pointcentralid.
     *
     * @return int|null
     */
    public function getPointcentralid()
    {
        return $this->pointcentralid;
    }

    /**
     * Set pointcentralconnected.
     *
     * @param bool $pointcentralconnected
     *
     * @return Properties
     */
    public function setPointcentralconnected($pointcentralconnected)
    {
        $this->pointcentralconnected = $pointcentralconnected;

        return $this;
    }

    /**
     * Get pointcentralconnected.
     *
     * @return bool
     */
    public function getPointcentralconnected()
    {
        return $this->pointcentralconnected;
    }

    /**
     * Set pointcentralmessage.
     *
     * @param string|null $pointcentralmessage
     *
     * @return Properties
     */
    public function setPointcentralmessage($pointcentralmessage = null)
    {
        $this->pointcentralmessage = $pointcentralmessage;

        return $this;
    }

    /**
     * Get pointcentralmessage.
     *
     * @return string|null
     */
    public function getPointcentralmessage()
    {
        return $this->pointcentralmessage;
    }

    /**
     * Set linkedpropertyid.
     *
     * @param int|null $linkedpropertyid
     *
     * @return Properties
     */
    public function setLinkedpropertyid($linkedpropertyid = null)
    {
        $this->linkedpropertyid = $linkedpropertyid;

        return $this;
    }

    /**
     * Get linkedpropertyid.
     *
     * @return int|null
     */
    public function getLinkedpropertyid()
    {
        return $this->linkedpropertyid;
    }

    /**
     * Set linkedpropertycode.
     *
     * @param string|null $linkedpropertycode
     *
     * @return Properties
     */
    public function setLinkedpropertycode($linkedpropertycode = null)
    {
        $this->linkedpropertycode = $linkedpropertycode;

        return $this;
    }

    /**
     * Get linkedpropertycode.
     *
     * @return string|null
     */
    public function getLinkedpropertycode()
    {
        return $this->linkedpropertycode;
    }

    /**
     * Set icallink.
     *
     * @param string|null $icallink
     *
     * @return Properties
     */
    public function setIcallink($icallink = null)
    {
        $this->icallink = $icallink;

        return $this;
    }

    /**
     * Get icallink.
     *
     * @return string|null
     */
    public function getIcallink()
    {
        return $this->icallink;
    }

    /**
     * Set icallinkstatus.
     *
     * @param string|null $icallinkstatus
     *
     * @return Properties
     */
    public function setIcallinkstatus($icallinkstatus = null)
    {
        $this->icallinkstatus = $icallinkstatus;

        return $this;
    }

    /**
     * Get icallinkstatus.
     *
     * @return string|null
     */
    public function getIcallinkstatus()
    {
        return $this->icallinkstatus;
    }

    /**
     * Set icallinktry.
     *
     * @param int|null $icallinktry
     *
     * @return Properties
     */
    public function setIcallinktry($icallinktry = null)
    {
        $this->icallinktry = $icallinktry;

        return $this;
    }

    /**
     * Get icallinktry.
     *
     * @return int|null
     */
    public function getIcallinktry()
    {
        return $this->icallinktry;
    }

    /**
     * Set importblocks.
     *
     * @param bool|null $importblocks
     *
     * @return Properties
     */
    public function setImportblocks($importblocks = null)
    {
        $this->importblocks = $importblocks;

        return $this;
    }

    /**
     * Get importblocks.
     *
     * @return bool|null
     */
    public function getImportblocks()
    {
        return $this->importblocks;
    }

    /**
     * Set icallink4.
     *
     * @param string|null $icallink4
     *
     * @return Properties
     */
    public function setIcallink4($icallink4 = null)
    {
        $this->icallink4 = $icallink4;

        return $this;
    }

    /**
     * Get icallink4.
     *
     * @return string|null
     */
    public function getIcallink4()
    {
        return $this->icallink4;
    }

    /**
     * Set icallink4status.
     *
     * @param string|null $icallink4status
     *
     * @return Properties
     */
    public function setIcallink4status($icallink4status = null)
    {
        $this->icallink4status = $icallink4status;

        return $this;
    }

    /**
     * Get icallink4status.
     *
     * @return string|null
     */
    public function getIcallink4status()
    {
        return $this->icallink4status;
    }

    /**
     * Set icallinktry4.
     *
     * @param int|null $icallinktry4
     *
     * @return Properties
     */
    public function setIcallinktry4($icallinktry4 = null)
    {
        $this->icallinktry4 = $icallinktry4;

        return $this;
    }

    /**
     * Get icallinktry4.
     *
     * @return int|null
     */
    public function getIcallinktry4()
    {
        return $this->icallinktry4;
    }

    /**
     * Set importblocks4.
     *
     * @param bool|null $importblocks4
     *
     * @return Properties
     */
    public function setImportblocks4($importblocks4 = null)
    {
        $this->importblocks4 = $importblocks4;

        return $this;
    }

    /**
     * Get importblocks4.
     *
     * @return bool|null
     */
    public function getImportblocks4()
    {
        return $this->importblocks4;
    }

    /**
     * Set icallink3.
     *
     * @param string|null $icallink3
     *
     * @return Properties
     */
    public function setIcallink3($icallink3 = null)
    {
        $this->icallink3 = $icallink3;

        return $this;
    }

    /**
     * Get icallink3.
     *
     * @return string|null
     */
    public function getIcallink3()
    {
        return $this->icallink3;
    }

    /**
     * Set icallink3status.
     *
     * @param string|null $icallink3status
     *
     * @return Properties
     */
    public function setIcallink3status($icallink3status = null)
    {
        $this->icallink3status = $icallink3status;

        return $this;
    }

    /**
     * Get icallink3status.
     *
     * @return string|null
     */
    public function getIcallink3status()
    {
        return $this->icallink3status;
    }

    /**
     * Set icallinktry3.
     *
     * @param int|null $icallinktry3
     *
     * @return Properties
     */
    public function setIcallinktry3($icallinktry3 = null)
    {
        $this->icallinktry3 = $icallinktry3;

        return $this;
    }

    /**
     * Get icallinktry3.
     *
     * @return int|null
     */
    public function getIcallinktry3()
    {
        return $this->icallinktry3;
    }

    /**
     * Set importblocks3.
     *
     * @param bool|null $importblocks3
     *
     * @return Properties
     */
    public function setImportblocks3($importblocks3 = null)
    {
        $this->importblocks3 = $importblocks3;

        return $this;
    }

    /**
     * Get importblocks3.
     *
     * @return bool|null
     */
    public function getImportblocks3()
    {
        return $this->importblocks3;
    }

    /**
     * Set icallink2.
     *
     * @param string|null $icallink2
     *
     * @return Properties
     */
    public function setIcallink2($icallink2 = null)
    {
        $this->icallink2 = $icallink2;

        return $this;
    }

    /**
     * Get icallink2.
     *
     * @return string|null
     */
    public function getIcallink2()
    {
        return $this->icallink2;
    }

    /**
     * Set icallink2status.
     *
     * @param string|null $icallink2status
     *
     * @return Properties
     */
    public function setIcallink2status($icallink2status = null)
    {
        $this->icallink2status = $icallink2status;

        return $this;
    }

    /**
     * Get icallink2status.
     *
     * @return string|null
     */
    public function getIcallink2status()
    {
        return $this->icallink2status;
    }

    /**
     * Set icallinktry2.
     *
     * @param int|null $icallinktry2
     *
     * @return Properties
     */
    public function setIcallinktry2($icallinktry2 = null)
    {
        $this->icallinktry2 = $icallinktry2;

        return $this;
    }

    /**
     * Get icallinktry2.
     *
     * @return int|null
     */
    public function getIcallinktry2()
    {
        return $this->icallinktry2;
    }

    /**
     * Set importblocks2.
     *
     * @param bool|null $importblocks2
     *
     * @return Properties
     */
    public function setImportblocks2($importblocks2 = null)
    {
        $this->importblocks2 = $importblocks2;

        return $this;
    }

    /**
     * Get importblocks2.
     *
     * @return bool|null
     */
    public function getImportblocks2()
    {
        return $this->importblocks2;
    }

    /**
     * Set json.
     *
     * @param string|null $json
     *
     * @return Properties
     */
    public function setJson($json = null)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Get json.
     *
     * @return string|null
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * Set jsonstatus.
     *
     * @param string|null $jsonstatus
     *
     * @return Properties
     */
    public function setJsonstatus($jsonstatus = null)
    {
        $this->jsonstatus = $jsonstatus;

        return $this;
    }

    /**
     * Get jsonstatus.
     *
     * @return string|null
     */
    public function getJsonstatus()
    {
        return $this->jsonstatus;
    }

    /**
     * Set jsontry.
     *
     * @param int|null $jsontry
     *
     * @return Properties
     */
    public function setJsontry($jsontry = null)
    {
        $this->jsontry = $jsontry;

        return $this;
    }

    /**
     * Get jsontry.
     *
     * @return int|null
     */
    public function getJsontry()
    {
        return $this->jsontry;
    }

    /**
     * Set performingimport.
     *
     * @param bool $performingimport
     *
     * @return Properties
     */
    public function setPerformingimport($performingimport)
    {
        $this->performingimport = $performingimport;

        return $this;
    }

    /**
     * Get performingimport.
     *
     * @return bool
     */
    public function getPerformingimport()
    {
        return $this->performingimport;
    }

    /**
     * Set performingimportdate.
     *
     * @param \DateTime|null $performingimportdate
     *
     * @return Properties
     */
    public function setPerformingimportdate($performingimportdate = null)
    {
        $this->performingimportdate = $performingimportdate;

        return $this;
    }

    /**
     * Get performingimportdate.
     *
     * @return \DateTime|null
     */
    public function getPerformingimportdate()
    {
        return $this->performingimportdate;
    }

    /**
     * Set importissuecount.
     *
     * @param int $importissuecount
     *
     * @return Properties
     */
    public function setImportissuecount($importissuecount)
    {
        $this->importissuecount = $importissuecount;

        return $this;
    }

    /**
     * Get importissuecount.
     *
     * @return int
     */
    public function getImportissuecount()
    {
        return $this->importissuecount;
    }

    /**
     * Set importissuenote.
     *
     * @param string|null $importissuenote
     *
     * @return Properties
     */
    public function setImportissuenote($importissuenote = null)
    {
        $this->importissuenote = $importissuenote;

        return $this;
    }

    /**
     * Get importissuenote.
     *
     * @return string|null
     */
    public function getImportissuenote()
    {
        return $this->importissuenote;
    }

    /**
     * Set propertyname.
     *
     * @param string $propertyname
     *
     * @return Properties
     */
    public function setPropertyname($propertyname)
    {
        $this->propertyname = $propertyname;

        return $this;
    }

    /**
     * Get propertyname.
     *
     * @return string
     */
    public function getPropertyname()
    {
        return $this->propertyname;
    }

    /**
     * Set propertyabbreviation.
     *
     * @param string|null $propertyabbreviation
     *
     * @return Properties
     */
    public function setPropertyabbreviation($propertyabbreviation = null)
    {
        $this->propertyabbreviation = $propertyabbreviation;

        return $this;
    }

    /**
     * Get propertyabbreviation.
     *
     * @return string|null
     */
    public function getPropertyabbreviation()
    {
        return $this->propertyabbreviation;
    }

    /**
     * Set bookingsoftwarename.
     *
     * @param string|null $bookingsoftwarename
     *
     * @return Properties
     */
    public function setBookingsoftwarename($bookingsoftwarename = null)
    {
        $this->bookingsoftwarename = $bookingsoftwarename;

        return $this;
    }

    /**
     * Get bookingsoftwarename.
     *
     * @return string|null
     */
    public function getBookingsoftwarename()
    {
        return $this->bookingsoftwarename;
    }

    /**
     * Set defaultcheckintime.
     *
     * @param int $defaultcheckintime
     *
     * @return Properties
     */
    public function setDefaultcheckintime($defaultcheckintime)
    {
        $this->defaultcheckintime = $defaultcheckintime;

        return $this;
    }

    /**
     * Get defaultcheckintime.
     *
     * @return int
     */
    public function getDefaultcheckintime()
    {
        return $this->defaultcheckintime;
    }

    /**
     * Set defaultcheckintimeminutes.
     *
     * @param int $defaultcheckintimeminutes
     *
     * @return Properties
     */
    public function setDefaultcheckintimeminutes($defaultcheckintimeminutes)
    {
        $this->defaultcheckintimeminutes = $defaultcheckintimeminutes;

        return $this;
    }

    /**
     * Get defaultcheckintimeminutes.
     *
     * @return int
     */
    public function getDefaultcheckintimeminutes()
    {
        return $this->defaultcheckintimeminutes;
    }

    /**
     * Set defaultcheckouttime.
     *
     * @param int $defaultcheckouttime
     *
     * @return Properties
     */
    public function setDefaultcheckouttime($defaultcheckouttime)
    {
        $this->defaultcheckouttime = $defaultcheckouttime;

        return $this;
    }

    /**
     * Get defaultcheckouttime.
     *
     * @return int
     */
    public function getDefaultcheckouttime()
    {
        return $this->defaultcheckouttime;
    }

    /**
     * Set defaultcheckouttimeminutes.
     *
     * @param int $defaultcheckouttimeminutes
     *
     * @return Properties
     */
    public function setDefaultcheckouttimeminutes($defaultcheckouttimeminutes)
    {
        $this->defaultcheckouttimeminutes = $defaultcheckouttimeminutes;

        return $this;
    }

    /**
     * Get defaultcheckouttimeminutes.
     *
     * @return int
     */
    public function getDefaultcheckouttimeminutes()
    {
        return $this->defaultcheckouttimeminutes;
    }

    /**
     * Set address.
     *
     * @param string|null $address
     *
     * @return Properties
     */
    public function setAddress($address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set lat.
     *
     * @param float|null $lat
     *
     * @return Properties
     */
    public function setLat($lat = null)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat.
     *
     * @return float|null
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon.
     *
     * @param float|null $lon
     *
     * @return Properties
     */
    public function setLon($lon = null)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon.
     *
     * @return float|null
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set cantfindlatlon.
     *
     * @param bool $cantfindlatlon
     *
     * @return Properties
     */
    public function setCantfindlatlon($cantfindlatlon)
    {
        $this->cantfindlatlon = $cantfindlatlon;

        return $this;
    }

    /**
     * Get cantfindlatlon.
     *
     * @return bool
     */
    public function getCantfindlatlon()
    {
        return $this->cantfindlatlon;
    }

    /**
     * Set propertynotes.
     *
     * @param string|null $propertynotes
     *
     * @return Properties
     */
    public function setPropertynotes($propertynotes = null)
    {
        $this->propertynotes = $propertynotes;

        return $this;
    }

    /**
     * Get propertynotes.
     *
     * @return string|null
     */
    public function getPropertynotes()
    {
        return $this->propertynotes;
    }

    /**
     * Set doorcode.
     *
     * @param string|null $doorcode
     *
     * @return Properties
     */
    public function setDoorcode($doorcode = null)
    {
        $this->doorcode = $doorcode;

        return $this;
    }

    /**
     * Get doorcode.
     *
     * @return string|null
     */
    public function getDoorcode()
    {
        return $this->doorcode;
    }

    /**
     * Set image1.
     *
     * @param string|null $image1
     *
     * @return Properties
     */
    public function setImage1($image1 = null)
    {
        $this->image1 = $image1;

        return $this;
    }

    /**
     * Get image1.
     *
     * @return string|null
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * Set image2.
     *
     * @param string|null $image2
     *
     * @return Properties
     */
    public function setImage2($image2 = null)
    {
        $this->image2 = $image2;

        return $this;
    }

    /**
     * Get image2.
     *
     * @return string|null
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * Set propertyfile.
     *
     * @param string|null $propertyfile
     *
     * @return Properties
     */
    public function setPropertyfile($propertyfile = null)
    {
        $this->propertyfile = $propertyfile;

        return $this;
    }

    /**
     * Get propertyfile.
     *
     * @return string|null
     */
    public function getPropertyfile()
    {
        return $this->propertyfile;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Properties
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set internalnotes.
     *
     * @param string|null $internalnotes
     *
     * @return Properties
     */
    public function setInternalnotes($internalnotes = null)
    {
        $this->internalnotes = $internalnotes;

        return $this;
    }

    /**
     * Get internalnotes.
     *
     * @return string|null
     */
    public function getInternalnotes()
    {
        return $this->internalnotes;
    }

    /**
     * Set onboardingbookingschecked.
     *
     * @param bool $onboardingbookingschecked
     *
     * @return Properties
     */
    public function setOnboardingbookingschecked($onboardingbookingschecked)
    {
        $this->onboardingbookingschecked = $onboardingbookingschecked;

        return $this;
    }

    /**
     * Get onboardingbookingschecked.
     *
     * @return bool
     */
    public function getOnboardingbookingschecked()
    {
        return $this->onboardingbookingschecked;
    }

    /**
     * Set hasnobookings.
     *
     * @param bool $hasnobookings
     *
     * @return Properties
     */
    public function setHasnobookings($hasnobookings)
    {
        $this->hasnobookings = $hasnobookings;

        return $this;
    }

    /**
     * Get hasnobookings.
     *
     * @return bool
     */
    public function getHasnobookings()
    {
        return $this->hasnobookings;
    }

    /**
     * Set bookingimportlinksunavailable.
     *
     * @param bool $bookingimportlinksunavailable
     *
     * @return Properties
     */
    public function setBookingimportlinksunavailable($bookingimportlinksunavailable)
    {
        $this->bookingimportlinksunavailable = $bookingimportlinksunavailable;

        return $this;
    }

    /**
     * Get bookingimportlinksunavailable.
     *
     * @return bool
     */
    public function getBookingimportlinksunavailable()
    {
        return $this->bookingimportlinksunavailable;
    }

    /**
     * Set onboardingownerverified.
     *
     * @param bool $onboardingownerverified
     *
     * @return Properties
     */
    public function setOnboardingownerverified($onboardingownerverified)
    {
        $this->onboardingownerverified = $onboardingownerverified;

        return $this;
    }

    /**
     * Get onboardingownerverified.
     *
     * @return bool
     */
    public function getOnboardingownerverified()
    {
        return $this->onboardingownerverified;
    }

    /**
     * Set setupcomplete.
     *
     * @param bool $setupcomplete
     *
     * @return Properties
     */
    public function setSetupcomplete($setupcomplete)
    {
        $this->setupcomplete = $setupcomplete;

        return $this;
    }

    /**
     * Get setupcomplete.
     *
     * @return bool
     */
    public function getSetupcomplete()
    {
        return $this->setupcomplete;
    }

    /**
     * Set accountname.
     *
     * @param string|null $accountname
     *
     * @return Properties
     */
    public function setAccountname($accountname = null)
    {
        $this->accountname = $accountname;

        return $this;
    }

    /**
     * Get accountname.
     *
     * @return string|null
     */
    public function getAccountname()
    {
        return $this->accountname;
    }

    /**
     * Set importcount.
     *
     * @param int $importcount
     *
     * @return Properties
     */
    public function setImportcount($importcount)
    {
        $this->importcount = $importcount;

        return $this;
    }

    /**
     * Get importcount.
     *
     * @return int
     */
    public function getImportcount()
    {
        return $this->importcount;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Properties
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder.
     *
     * @return int
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Properties
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Properties
     */
    public function setCreatedate($createdate)
    {
        $this->createdate = $createdate;

        return $this;
    }

    /**
     * Get createdate.
     *
     * @return \DateTime
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }

    /**
     * Set deletedate.
     *
     * @param \DateTime|null $deletedate
     *
     * @return Properties
     */
    public function setDeletedate($deletedate = null)
    {
        $this->deletedate = $deletedate;

        return $this;
    }

    /**
     * Get deletedate.
     *
     * @return \DateTime|null
     */
    public function getDeletedate()
    {
        return $this->deletedate;
    }

    /**
     * Set updatedate.
     *
     * @param \DateTime|null $updatedate
     *
     * @return Properties
     */
    public function setUpdatedate($updatedate = null)
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    /**
     * Get updatedate.
     *
     * @return \DateTime|null
     */
    public function getUpdatedate()
    {
        return $this->updatedate;
    }

    /**
     * Set opertostartdate.
     *
     * @param \DateTime|null $opertostartdate
     *
     * @return Properties
     */
    public function setOpertostartdate($opertostartdate = null)
    {
        $this->opertostartdate = $opertostartdate;

        return $this;
    }

    /**
     * Get opertostartdate.
     *
     * @return \DateTime|null
     */
    public function getOpertostartdate()
    {
        return $this->opertostartdate;
    }

    /**
     * Set opertoenddate.
     *
     * @param \DateTime|null $opertoenddate
     *
     * @return Properties
     */
    public function setOpertoenddate($opertoenddate = null)
    {
        $this->opertoenddate = $opertoenddate;

        return $this;
    }

    /**
     * Get opertoenddate.
     *
     * @return \DateTime|null
     */
    public function getOpertoenddate()
    {
        return $this->opertoenddate;
    }

    /**
     * Set customerid.
     *
     * @param \AppBundle\Entity\Customers|null $customerid
     *
     * @return Properties
     */
    public function setCustomerid(\AppBundle\Entity\Customers $customerid = null)
    {
        $this->customerid = $customerid;

        return $this;
    }

    /**
     * Get customerid.
     *
     * @return \AppBundle\Entity\Customers|null
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * Set ownerid.
     *
     * @param \AppBundle\Entity\Owners|null $ownerid
     *
     * @return Properties
     */
    public function setOwnerid(\AppBundle\Entity\Owners $ownerid = null)
    {
        $this->ownerid = $ownerid;

        return $this;
    }

    /**
     * Get ownerid.
     *
     * @return \AppBundle\Entity\Owners|null
     */
    public function getOwnerid()
    {
        return $this->ownerid;
    }

    /**
     * Set regionid.
     *
     * @param \AppBundle\Entity\Regions|null $regionid
     *
     * @return Properties
     */
    public function setRegionid(\AppBundle\Entity\Regions $regionid = null)
    {
        $this->regionid = $regionid;

        return $this;
    }

    /**
     * Get regionid.
     *
     * @return \AppBundle\Entity\Regions|null
     */
    public function getRegionid()
    {
        return $this->regionid;
    }
}
