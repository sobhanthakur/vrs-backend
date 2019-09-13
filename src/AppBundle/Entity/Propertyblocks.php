<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Propertyblocks
 *
 * @ORM\Table(name="PropertyBlocks", indexes={@ORM\Index(name="checkin", columns={"CheckIn"}), @ORM\Index(name="checkout", columns={"CheckOut"}), @ORM\Index(name="propertybookingid", columns={"PropertyBookingID"}), @ORM\Index(name="PropertyID", columns={"PropertyID"}), @ORM\Index(name="IDX_480EC5079E421AC2", columns={"IgnoreServicerID"})})
 * @ORM\Entity
 */
class Propertyblocks
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyBlockID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertyblockid;

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
     * @var \DateTime
     *
     * @ORM\Column(name="CheckOut", type="date", nullable=false)
     */
    private $checkout;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Guest", type="string", length=50, nullable=true)
     */
    private $guest;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Source", type="string", length=50, nullable=true)
     */
    private $source;

    /**
     * @var string|null
     *
     * @ORM\Column(name="AddError", type="text", length=-1, nullable=true)
     */
    private $adderror;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ReservationID", type="string", length=200, nullable=true)
     */
    private $reservationid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PropertyBookingID", type="integer", nullable=true)
     */
    private $propertybookingid = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="Ignore", type="boolean", nullable=false)
     */
    private $ignore = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="IgnoreDate", type="datetime", nullable=true)
     */
    private $ignoredate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

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
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IgnoreServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $ignoreservicerid;



    /**
     * Get propertyblockid.
     *
     * @return int
     */
    public function getPropertyblockid()
    {
        return $this->propertyblockid;
    }

    /**
     * Set propertyintegrationid.
     *
     * @param int|null $propertyintegrationid
     *
     * @return Propertyblocks
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
     * @return Propertyblocks
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
     * Set checkout.
     *
     * @param \DateTime $checkout
     *
     * @return Propertyblocks
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
     * Set guest.
     *
     * @param string|null $guest
     *
     * @return Propertyblocks
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
     * Set source.
     *
     * @param string|null $source
     *
     * @return Propertyblocks
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
     * Set adderror.
     *
     * @param string|null $adderror
     *
     * @return Propertyblocks
     */
    public function setAdderror($adderror = null)
    {
        $this->adderror = $adderror;

        return $this;
    }

    /**
     * Get adderror.
     *
     * @return string|null
     */
    public function getAdderror()
    {
        return $this->adderror;
    }

    /**
     * Set reservationid.
     *
     * @param string|null $reservationid
     *
     * @return Propertyblocks
     */
    public function setReservationid($reservationid = null)
    {
        $this->reservationid = $reservationid;

        return $this;
    }

    /**
     * Get reservationid.
     *
     * @return string|null
     */
    public function getReservationid()
    {
        return $this->reservationid;
    }

    /**
     * Set propertybookingid.
     *
     * @param int|null $propertybookingid
     *
     * @return Propertyblocks
     */
    public function setPropertybookingid($propertybookingid = null)
    {
        $this->propertybookingid = $propertybookingid;

        return $this;
    }

    /**
     * Get propertybookingid.
     *
     * @return int|null
     */
    public function getPropertybookingid()
    {
        return $this->propertybookingid;
    }

    /**
     * Set ignore.
     *
     * @param bool $ignore
     *
     * @return Propertyblocks
     */
    public function setIgnore($ignore)
    {
        $this->ignore = $ignore;

        return $this;
    }

    /**
     * Get ignore.
     *
     * @return bool
     */
    public function getIgnore()
    {
        return $this->ignore;
    }

    /**
     * Set ignoredate.
     *
     * @param \DateTime|null $ignoredate
     *
     * @return Propertyblocks
     */
    public function setIgnoredate($ignoredate = null)
    {
        $this->ignoredate = $ignoredate;

        return $this;
    }

    /**
     * Get ignoredate.
     *
     * @return \DateTime|null
     */
    public function getIgnoredate()
    {
        return $this->ignoredate;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Propertyblocks
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
     * @return Propertyblocks
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
     * Set ignoreservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $ignoreservicerid
     *
     * @return Propertyblocks
     */
    public function setIgnoreservicerid(\AppBundle\Entity\Servicers $ignoreservicerid = null)
    {
        $this->ignoreservicerid = $ignoreservicerid;

        return $this;
    }

    /**
     * Get ignoreservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getIgnoreservicerid()
    {
        return $this->ignoreservicerid;
    }
}
