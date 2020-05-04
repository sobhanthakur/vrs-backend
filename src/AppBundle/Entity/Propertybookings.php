<?php

namespace AppBundle\Entity;

use AppBundle\CustomClasses\DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Propertybookings
 *
 * @ORM\Table(name="PropertyBookings", indexes={@ORM\Index(name="active", columns={"Active"}), @ORM\Index(name="checkin", columns={"CheckIn"}), @ORM\Index(name="checkintime", columns={"CheckInTime"}), @ORM\Index(name="checkout", columns={"CheckOut"}), @ORM\Index(name="checkouttime", columns={"CheckOutTime"}), @ORM\Index(name="propertyid", columns={"PropertyID"}), @ORM\Index(name="PropertyIDCheckOutDeleteCount", columns={"PropertyID", "CheckOut", "DeleteCount"})})
 *  @ORM\Entity(repositoryClass="AppBundle\Repository\PropertybookingsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Propertybookings
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyBookingID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertybookingid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ImportBookingID", type="string", length=200, nullable=true)
     */
    private $importbookingid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PropertyIntegrationID", type="integer", nullable=true)
     */
    private $propertyintegrationid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CheckIn", type="date", nullable=false)
     */
    private $checkin;

    /**
     * @var int
     *
     * @ORM\Column(name="CheckInTime", type="integer", nullable=false)
     */
    private $checkintime;

    /**
     * @var int
     *
     * @ORM\Column(name="CheckInTimeMinutes", type="integer", nullable=false)
     */
    private $checkintimeminutes = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CheckOut", type="date", nullable=false)
     */
    private $checkout;

    /**
     * @var int
     *
     * @ORM\Column(name="CheckOutTime", type="integer", nullable=false)
     */
    private $checkouttime;

    /**
     * @var int
     *
     * @ORM\Column(name="CheckOutTimeMinutes", type="integer", nullable=false)
     */
    private $checkouttimeminutes = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Guest", type="string", length=100, nullable=true)
     */
    private $guest;

    /**
     * @var string|null
     *
     * @ORM\Column(name="GuestEmail", type="string", length=100, nullable=true)
     */
    private $guestemail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="GuestPhone", type="string", length=100, nullable=true)
     */
    private $guestphone;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NumberOfGuests", type="integer", nullable=true)
     */
    private $numberofguests;

    /**
     * @var int
     *
     * @ORM\Column(name="NumberOfChildren", type="integer", nullable=false)
     */
    private $numberofchildren = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="NumberOfPets", type="integer", nullable=true)
     */
    private $numberofpets = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="IsOwner", type="integer", nullable=false)
     */
    private $isowner = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="Rent", type="float", precision=53, scale=0, nullable=true)
     */
    private $rent;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Deposit", type="float", precision=53, scale=0, nullable=true)
     */
    private $deposit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="BookingTags", type="string", length=3000, nullable=true)
     */
    private $bookingtags;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ManualBookingTags", type="string", length=1000, nullable=true)
     */
    private $manualbookingtags;

    /**
     * @var string|null
     *
     * @ORM\Column(name="color", type="string", length=7, nullable=true, options={"fixed"=true})
     */
    private $color;

    /**
     * @var bool
     *
     * @ORM\Column(name="Marked", type="boolean", nullable=false)
     */
    private $marked = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Edited", type="boolean", nullable=false)
     */
    private $edited = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Source", type="string", length=50, nullable=true)
     */
    private $source;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SourceID", type="integer", nullable=true, options={"comment"="1=icallink1,2=icallink2,3=icallink3,4=icallink4,5=json"})
     */
    private $sourceid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iCALUID", type="string", length=200, nullable=true)
     */
    private $icaluid;

    /**
     * @var bool
     *
     * @ORM\Column(name="NewBooking", type="boolean", nullable=false)
     */
    private $newbooking = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="BackToBackStart", type="boolean", nullable=false)
     */
    private $backtobackstart = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="BackToBackEnd", type="boolean", nullable=false)
     */
    private $backtobackend = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="IsManuallyEntered", type="boolean", nullable=false)
     */
    private $ismanuallyentered = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="BH247CheckedOut", type="boolean", nullable=false)
     */
    private $bh247checkedout = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="LockingSystemCheckedOut", type="boolean", nullable=false)
     */
    private $lockingsystemcheckedout = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="GlobalNote", type="string", length=2000, nullable=true)
     */
    private $globalnote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="LinenCounts", type="string", length=2000, nullable=true)
     */
    private $linencounts;

    /**
     * @var string|null
     *
     * @ORM\Column(name="NextLinenCounts", type="string", length=2000, nullable=true)
     */
    private $nextlinencounts;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PMSHousekeepingNote", type="string", length=2000, nullable=true)
     */
    private $pmshousekeepingnote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="InGlobalNote", type="string", length=2000, nullable=true)
     */
    private $inglobalnote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OutGlobalNote", type="string", length=2000, nullable=true)
     */
    private $outglobalnote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="InternalNote", type="string", length=2000, nullable=true)
     */
    private $internalnote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="OwnerNote", type="string", length=2000, nullable=true)
     */
    private $ownernote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PMSNote", type="string", length=2000, nullable=true)
     */
    private $pmsnote;

    /**
     * @var string|null
     *
     * @ORM\Column(name="JillsNotes", type="text", length=-1, nullable=true)
     */
    private $jillsnotes;

    /**
     * @var float|null
     *
     * @ORM\Column(name="TotalIncome", type="float", precision=53, scale=0, nullable=true)
     */
    private $totalincome;

    /**
     * @var bool
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false, options={"default"="1"})
     */
    private $active = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="DeleteCount", type="integer", nullable=false)
     */
    private $deletecount = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Deleted", type="boolean", nullable=false)
     */
    private $deleted = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="DeletedDate", type="datetime", nullable=true)
     */
    private $deleteddate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="UpdateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $updatedate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDAte", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate;

    /**
     * @var \Properties
     *
     * @ORM\ManyToOne(targetEntity="Properties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyID", referencedColumnName="PropertyID")
     * })
     */
    private $propertyid;



    /**
     * Get propertybookingid.
     *
     * @return int
     */
    public function getPropertybookingid()
    {
        return $this->propertybookingid;
    }

    /**
     * Set importbookingid.
     *
     * @param string|null $importbookingid
     *
     * @return Propertybookings
     */
    public function setImportbookingid($importbookingid = null)
    {
        $this->importbookingid = $importbookingid;

        return $this;
    }

    /**
     * Get importbookingid.
     *
     * @return string|null
     */
    public function getImportbookingid()
    {
        return $this->importbookingid;
    }

    /**
     * Set propertyintegrationid.
     *
     * @param int|null $propertyintegrationid
     *
     * @return Propertybookings
     */
    public function setPropertyintegrationid($propertyintegrationid = null)
    {
        $this->propertyintegrationid = $propertyintegrationid;

        return $this;
    }

    /**
     * Get propertyintegrationid.
     *
     * @return int|null
     */
    public function getPropertyintegrationid()
    {
        return $this->propertyintegrationid;
    }

    /**
     * Set checkin.
     *
     * @param \DateTime $checkin
     *
     * @return Propertybookings
     */
    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;

        return $this;
    }

    /**
     * Get checkin.
     *
     * @return \DateTime
     */
    public function getCheckin()
    {
        return $this->checkin;
    }

    /**
     * Set checkintime.
     *
     * @param int $checkintime
     *
     * @return Propertybookings
     */
    public function setCheckintime($checkintime)
    {
        $this->checkintime = $checkintime;

        return $this;
    }

    /**
     * Get checkintime.
     *
     * @return int
     */
    public function getCheckintime()
    {
        return $this->checkintime;
    }

    /**
     * Set checkintimeminutes.
     *
     * @param int $checkintimeminutes
     *
     * @return Propertybookings
     */
    public function setCheckintimeminutes($checkintimeminutes)
    {
        $this->checkintimeminutes = $checkintimeminutes;

        return $this;
    }

    /**
     * Get checkintimeminutes.
     *
     * @return int
     */
    public function getCheckintimeminutes()
    {
        return $this->checkintimeminutes;
    }

    /**
     * Set checkout.
     *
     * @param \DateTime $checkout
     *
     * @return Propertybookings
     */
    public function setCheckout($checkout)
    {
        $this->checkout = $checkout;

        return $this;
    }

    /**
     * Get checkout.
     *
     * @return \DateTime
     */
    public function getCheckout()
    {
        return $this->checkout;
    }

    /**
     * Set checkouttime.
     *
     * @param int $checkouttime
     *
     * @return Propertybookings
     */
    public function setCheckouttime($checkouttime)
    {
        $this->checkouttime = $checkouttime;

        return $this;
    }

    /**
     * Get checkouttime.
     *
     * @return int
     */
    public function getCheckouttime()
    {
        return $this->checkouttime;
    }

    /**
     * Set checkouttimeminutes.
     *
     * @param int $checkouttimeminutes
     *
     * @return Propertybookings
     */
    public function setCheckouttimeminutes($checkouttimeminutes)
    {
        $this->checkouttimeminutes = $checkouttimeminutes;

        return $this;
    }

    /**
     * Get checkouttimeminutes.
     *
     * @return int
     */
    public function getCheckouttimeminutes()
    {
        return $this->checkouttimeminutes;
    }

    /**
     * Set guest.
     *
     * @param string|null $guest
     *
     * @return Propertybookings
     */
    public function setGuest($guest = null)
    {
        $this->guest = $guest;

        return $this;
    }

    /**
     * Get guest.
     *
     * @return string|null
     */
    public function getGuest()
    {
        return $this->guest;
    }

    /**
     * Set guestemail.
     *
     * @param string|null $guestemail
     *
     * @return Propertybookings
     */
    public function setGuestemail($guestemail = null)
    {
        $this->guestemail = $guestemail;

        return $this;
    }

    /**
     * Get guestemail.
     *
     * @return string|null
     */
    public function getGuestemail()
    {
        return $this->guestemail;
    }

    /**
     * Set guestphone.
     *
     * @param string|null $guestphone
     *
     * @return Propertybookings
     */
    public function setGuestphone($guestphone = null)
    {
        $this->guestphone = $guestphone;

        return $this;
    }

    /**
     * Get guestphone.
     *
     * @return string|null
     */
    public function getGuestphone()
    {
        return $this->guestphone;
    }

    /**
     * Set numberofguests.
     *
     * @param int|null $numberofguests
     *
     * @return Propertybookings
     */
    public function setNumberofguests($numberofguests = null)
    {
        $this->numberofguests = $numberofguests;

        return $this;
    }

    /**
     * Get numberofguests.
     *
     * @return int|null
     */
    public function getNumberofguests()
    {
        return $this->numberofguests;
    }

    /**
     * Set numberofchildren.
     *
     * @param int $numberofchildren
     *
     * @return Propertybookings
     */
    public function setNumberofchildren($numberofchildren)
    {
        $this->numberofchildren = $numberofchildren;

        return $this;
    }

    /**
     * Get numberofchildren.
     *
     * @return int
     */
    public function getNumberofchildren()
    {
        return $this->numberofchildren;
    }

    /**
     * Set numberofpets.
     *
     * @param int|null $numberofpets
     *
     * @return Propertybookings
     */
    public function setNumberofpets($numberofpets = null)
    {
        $this->numberofpets = $numberofpets;

        return $this;
    }

    /**
     * Get numberofpets.
     *
     * @return int|null
     */
    public function getNumberofpets()
    {
        return $this->numberofpets;
    }

    /**
     * Set isowner.
     *
     * @param int $isowner
     *
     * @return Propertybookings
     */
    public function setIsowner($isowner)
    {
        $this->isowner = $isowner;

        return $this;
    }

    /**
     * Get isowner.
     *
     * @return int
     */
    public function getIsowner()
    {
        return $this->isowner;
    }

    /**
     * Set rent.
     *
     * @param float|null $rent
     *
     * @return Propertybookings
     */
    public function setRent($rent = null)
    {
        $this->rent = $rent;

        return $this;
    }

    /**
     * Get rent.
     *
     * @return float|null
     */
    public function getRent()
    {
        return $this->rent;
    }

    /**
     * Set deposit.
     *
     * @param float|null $deposit
     *
     * @return Propertybookings
     */
    public function setDeposit($deposit = null)
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * Get deposit.
     *
     * @return float|null
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Set bookingtags.
     *
     * @param string|null $bookingtags
     *
     * @return Propertybookings
     */
    public function setBookingtags($bookingtags = null)
    {
        $this->bookingtags = $bookingtags;

        return $this;
    }

    /**
     * Get bookingtags.
     *
     * @return string|null
     */
    public function getBookingtags()
    {
        return $this->bookingtags;
    }

    /**
     * Set manualbookingtags.
     *
     * @param string|null $manualbookingtags
     *
     * @return Propertybookings
     */
    public function setManualbookingtags($manualbookingtags = null)
    {
        $this->manualbookingtags = $manualbookingtags;

        return $this;
    }

    /**
     * Get manualbookingtags.
     *
     * @return string|null
     */
    public function getManualbookingtags()
    {
        return $this->manualbookingtags;
    }

    /**
     * Set color.
     *
     * @param string|null $color
     *
     * @return Propertybookings
     */
    public function setColor($color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set marked.
     *
     * @param bool $marked
     *
     * @return Propertybookings
     */
    public function setMarked($marked)
    {
        $this->marked = $marked;

        return $this;
    }

    /**
     * Get marked.
     *
     * @return bool
     */
    public function getMarked()
    {
        return $this->marked;
    }

    /**
     * Set edited.
     *
     * @param bool $edited
     *
     * @return Propertybookings
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;

        return $this;
    }

    /**
     * Get edited.
     *
     * @return bool
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * Set source.
     *
     * @param string|null $source
     *
     * @return Propertybookings
     */
    public function setSource($source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return string|null
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set sourceid.
     *
     * @param int|null $sourceid
     *
     * @return Propertybookings
     */
    public function setSourceid($sourceid = null)
    {
        $this->sourceid = $sourceid;

        return $this;
    }

    /**
     * Get sourceid.
     *
     * @return int|null
     */
    public function getSourceid()
    {
        return $this->sourceid;
    }

    /**
     * Set icaluid.
     *
     * @param string|null $icaluid
     *
     * @return Propertybookings
     */
    public function setIcaluid($icaluid = null)
    {
        $this->icaluid = $icaluid;

        return $this;
    }

    /**
     * Get icaluid.
     *
     * @return string|null
     */
    public function getIcaluid()
    {
        return $this->icaluid;
    }

    /**
     * Set newbooking.
     *
     * @param bool $newbooking
     *
     * @return Propertybookings
     */
    public function setNewbooking($newbooking)
    {
        $this->newbooking = $newbooking;

        return $this;
    }

    /**
     * Get newbooking.
     *
     * @return bool
     */
    public function getNewbooking()
    {
        return $this->newbooking;
    }

    /**
     * Set backtobackstart.
     *
     * @param bool $backtobackstart
     *
     * @return Propertybookings
     */
    public function setBacktobackstart($backtobackstart)
    {
        $this->backtobackstart = $backtobackstart;

        return $this;
    }

    /**
     * Get backtobackstart.
     *
     * @return bool
     */
    public function getBacktobackstart()
    {
        return $this->backtobackstart;
    }

    /**
     * Set backtobackend.
     *
     * @param bool $backtobackend
     *
     * @return Propertybookings
     */
    public function setBacktobackend($backtobackend)
    {
        $this->backtobackend = $backtobackend;

        return $this;
    }

    /**
     * Get backtobackend.
     *
     * @return bool
     */
    public function getBacktobackend()
    {
        return $this->backtobackend;
    }

    /**
     * Set ismanuallyentered.
     *
     * @param bool $ismanuallyentered
     *
     * @return Propertybookings
     */
    public function setIsmanuallyentered($ismanuallyentered)
    {
        $this->ismanuallyentered = $ismanuallyentered;

        return $this;
    }

    /**
     * Get ismanuallyentered.
     *
     * @return bool
     */
    public function getIsmanuallyentered()
    {
        return $this->ismanuallyentered;
    }

    /**
     * Set bh247checkedout.
     *
     * @param bool $bh247checkedout
     *
     * @return Propertybookings
     */
    public function setBh247checkedout($bh247checkedout)
    {
        $this->bh247checkedout = $bh247checkedout;

        return $this;
    }

    /**
     * Get bh247checkedout.
     *
     * @return bool
     */
    public function getBh247checkedout()
    {
        return $this->bh247checkedout;
    }

    /**
     * Set lockingsystemcheckedout.
     *
     * @param bool $lockingsystemcheckedout
     *
     * @return Propertybookings
     */
    public function setLockingsystemcheckedout($lockingsystemcheckedout)
    {
        $this->lockingsystemcheckedout = $lockingsystemcheckedout;

        return $this;
    }

    /**
     * Get lockingsystemcheckedout.
     *
     * @return bool
     */
    public function getLockingsystemcheckedout()
    {
        return $this->lockingsystemcheckedout;
    }

    /**
     * Set globalnote.
     *
     * @param string|null $globalnote
     *
     * @return Propertybookings
     */
    public function setGlobalnote($globalnote = null)
    {
        $this->globalnote = $globalnote;

        return $this;
    }

    /**
     * Get globalnote.
     *
     * @return string|null
     */
    public function getGlobalnote()
    {
        return $this->globalnote;
    }

    /**
     * Set inglobalnote.
     *
     * @param string|null $inglobalnote
     *
     * @return Propertybookings
     */
    public function setInglobalnote($inglobalnote = null)
    {
        $this->inglobalnote = $inglobalnote;

        return $this;
    }

    /**
     * Get inglobalnote.
     *
     * @return string|null
     */
    public function getInglobalnote()
    {
        return $this->inglobalnote;
    }

    /**
     * Set outglobalnote.
     *
     * @param string|null $outglobalnote
     *
     * @return Propertybookings
     */
    public function setOutglobalnote($outglobalnote = null)
    {
        $this->outglobalnote = $outglobalnote;

        return $this;
    }

    /**
     * Get outglobalnote.
     *
     * @return string|null
     */
    public function getOutglobalnote()
    {
        return $this->outglobalnote;
    }

    /**
     * Set internalnote.
     *
     * @param string|null $internalnote
     *
     * @return Propertybookings
     */
    public function setInternalnote($internalnote = null)
    {
        $this->internalnote = $internalnote;

        return $this;
    }

    /**
     * Get internalnote.
     *
     * @return string|null
     */
    public function getInternalnote()
    {
        return $this->internalnote;
    }

    /**
     * Set ownernote.
     *
     * @param string|null $ownernote
     *
     * @return Propertybookings
     */
    public function setOwnernote($ownernote = null)
    {
        $this->ownernote = $ownernote;

        return $this;
    }

    /**
     * Get ownernote.
     *
     * @return string|null
     */
    public function getOwnernote()
    {
        return $this->ownernote;
    }

    /**
     * Set pmsnote.
     *
     * @param string|null $pmsnote
     *
     * @return Propertybookings
     */
    public function setPmsnote($pmsnote = null)
    {
        $this->pmsnote = $pmsnote;

        return $this;
    }

    /**
     * Get pmsnote.
     *
     * @return string|null
     */
    public function getPmsnote()
    {
        return $this->pmsnote;
    }

    /**
     * Set jillsnotes.
     *
     * @param string|null $jillsnotes
     *
     * @return Propertybookings
     */
    public function setJillsnotes($jillsnotes = null)
    {
        $this->jillsnotes = $jillsnotes;

        return $this;
    }

    /**
     * Get jillsnotes.
     *
     * @return string|null
     */
    public function getJillsnotes()
    {
        return $this->jillsnotes;
    }

    /**
     * Set totalincome.
     *
     * @param float|null $totalincome
     *
     * @return Propertybookings
     */
    public function setTotalincome($totalincome = null)
    {
        $this->totalincome = $totalincome;

        return $this;
    }

    /**
     * Get totalincome.
     *
     * @return float|null
     */
    public function getTotalincome()
    {
        return $this->totalincome;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Propertybookings
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
     * Set deletecount.
     *
     * @param int $deletecount
     *
     * @return Propertybookings
     */
    public function setDeletecount($deletecount)
    {
        $this->deletecount = $deletecount;

        return $this;
    }

    /**
     * Get deletecount.
     *
     * @return int
     */
    public function getDeletecount()
    {
        return $this->deletecount;
    }

    /**
     * Set deleted.
     *
     * @param bool $deleted
     *
     * @return Propertybookings
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted.
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deleteddate.
     *
     * @param \DateTime|null $deleteddate
     *
     * @return Propertybookings
     */
    public function setDeleteddate($deleteddate = null)
    {
        $this->deleteddate = $deleteddate;

        return $this;
    }

    /**
     * Get deleteddate.
     *
     * @return \DateTime|null
     */
    public function getDeleteddate()
    {
        return $this->deleteddate;
    }

    /**
     * Set updatedate.
     *
     * @param \DateTime $updatedate
     *
     * @return Propertybookings
     */
    public function setUpdatedate($updatedate)
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    /**
     * Get updatedate.
     *
     * @return \DateTime
     */
    public function getUpdatedate()
    {
        return $this->updatedate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Propertybookings
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
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Propertybookings
     */
    public function setPropertyid(\AppBundle\Entity\Properties $propertyid = null)
    {
        $this->propertyid = $propertyid;

        return $this;
    }

    /**
     * Get propertyid.
     *
     * @return \AppBundle\Entity\Properties|null
     */
    public function getPropertyid()
    {
        return $this->propertyid;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdate = new \DateTime();
        $this->updatedate = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedate = new \DateTime();
    }


    /**
     * Set pmshousekeepingnote.
     *
     * @param string|null $pmshousekeepingnote
     *
     * @return Propertybookings
     */
    public function setPmshousekeepingnote($pmshousekeepingnote = null)
    {
        $this->pmshousekeepingnote = $pmshousekeepingnote;

        return $this;
    }

    /**
     * Get pmshousekeepingnote.
     *
     * @return string|null
     */
    public function getPmshousekeepingnote()
    {
        return $this->pmshousekeepingnote;
    }

    /**
     * Set linencounts.
     *
     * @param string|null $linencounts
     *
     * @return Propertybookings
     */
    public function setLinencounts($linencounts = null)
    {
        $this->linencounts = $linencounts;

        return $this;
    }

    /**
     * Get linencounts.
     *
     * @return string|null
     */
    public function getLinencounts()
    {
        return $this->linencounts;
    }
}
