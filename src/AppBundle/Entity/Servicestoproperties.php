<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Servicestoproperties
 *
 * @ORM\Table(name="ServicesToProperties", indexes={@ORM\Index(name="defaultservicerid", columns={"DefaultServicerID"}), @ORM\Index(name="PropertyID", columns={"PropertyID"}), @ORM\Index(name="ServiceID", columns={"ServiceID"}), @ORM\Index(name="IDX_40044863B650950C", columns={"ChecklistID"})})
 * @ORM\Entity
 */
class Servicestoproperties
{
    /**
     * @var int
     *
     * @ORM\Column(name="ServiceToPropertyID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $servicetopropertyid;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Amount", type="float", precision=53, scale=0, nullable=true)
     */
    private $amount;

    /**
     * @var float|null
     *
     * @ORM\Column(name="MinTimeToComplete", type="float", precision=53, scale=0, nullable=true)
     */
    private $mintimetocomplete;

    /**
     * @var float|null
     *
     * @ORM\Column(name="MaxTimeToComplete", type="float", precision=53, scale=0, nullable=true)
     */
    private $maxtimetocomplete;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NumberOfServicers", type="integer", nullable=true)
     */
    private $numberofservicers;

    /**
     * @var float|null
     *
     * @ORM\Column(name="PiecePay", type="float", precision=53, scale=0, nullable=true)
     */
    private $piecepay;

    /**
     * @var float|null
     *
     * @ORM\Column(name="LaborAmount", type="float", precision=53, scale=0, nullable=true)
     */
    private $laboramount;

    /**
     * @var float|null
     *
     * @ORM\Column(name="MaterialsAmount", type="float", precision=53, scale=0, nullable=true)
     */
    private $materialsamount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Services
     *
     * @ORM\ManyToOne(targetEntity="Services")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServiceID", referencedColumnName="ServiceID")
     * })
     */
    private $serviceid;

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
     * @var \Checklists
     *
     * @ORM\ManyToOne(targetEntity="Checklists")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ChecklistID", referencedColumnName="ChecklistID")
     * })
     */
    private $checklistid;

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="DefaultServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $defaultservicerid;



    /**
     * Get servicetopropertyid.
     *
     * @return int
     */
    public function getServicetopropertyid()
    {
        return $this->servicetopropertyid;
    }

    /**
     * Set amount.
     *
     * @param float|null $amount
     *
     * @return Servicestoproperties
     */
    public function setAmount($amount = null)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set mintimetocomplete.
     *
     * @param float|null $mintimetocomplete
     *
     * @return Servicestoproperties
     */
    public function setMintimetocomplete($mintimetocomplete = null)
    {
        $this->mintimetocomplete = $mintimetocomplete;

        return $this;
    }

    /**
     * Get mintimetocomplete.
     *
     * @return float|null
     */
    public function getMintimetocomplete()
    {
        return $this->mintimetocomplete;
    }

    /**
     * Set maxtimetocomplete.
     *
     * @param float|null $maxtimetocomplete
     *
     * @return Servicestoproperties
     */
    public function setMaxtimetocomplete($maxtimetocomplete = null)
    {
        $this->maxtimetocomplete = $maxtimetocomplete;

        return $this;
    }

    /**
     * Get maxtimetocomplete.
     *
     * @return float|null
     */
    public function getMaxtimetocomplete()
    {
        return $this->maxtimetocomplete;
    }

    /**
     * Set numberofservicers.
     *
     * @param int|null $numberofservicers
     *
     * @return Servicestoproperties
     */
    public function setNumberofservicers($numberofservicers = null)
    {
        $this->numberofservicers = $numberofservicers;

        return $this;
    }

    /**
     * Get numberofservicers.
     *
     * @return int|null
     */
    public function getNumberofservicers()
    {
        return $this->numberofservicers;
    }

    /**
     * Set piecepay.
     *
     * @param float|null $piecepay
     *
     * @return Servicestoproperties
     */
    public function setPiecepay($piecepay = null)
    {
        $this->piecepay = $piecepay;

        return $this;
    }

    /**
     * Get piecepay.
     *
     * @return float|null
     */
    public function getPiecepay()
    {
        return $this->piecepay;
    }

    /**
     * Set laboramount.
     *
     * @param float|null $laboramount
     *
     * @return Servicestoproperties
     */
    public function setLaboramount($laboramount = null)
    {
        $this->laboramount = $laboramount;

        return $this;
    }

    /**
     * Get laboramount.
     *
     * @return float|null
     */
    public function getLaboramount()
    {
        return $this->laboramount;
    }

    /**
     * Set materialsamount.
     *
     * @param float|null $materialsamount
     *
     * @return Servicestoproperties
     */
    public function setMaterialsamount($materialsamount = null)
    {
        $this->materialsamount = $materialsamount;

        return $this;
    }

    /**
     * Get materialsamount.
     *
     * @return float|null
     */
    public function getMaterialsamount()
    {
        return $this->materialsamount;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Servicestoproperties
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
     * Set serviceid.
     *
     * @param \AppBundle\Entity\Services|null $serviceid
     *
     * @return Servicestoproperties
     */
    public function setServiceid(\AppBundle\Entity\Services $serviceid = null)
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    /**
     * Get serviceid.
     *
     * @return \AppBundle\Entity\Services|null
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }

    /**
     * Set propertyid.
     *
     * @param \AppBundle\Entity\Properties|null $propertyid
     *
     * @return Servicestoproperties
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
     * Set checklistid.
     *
     * @param \AppBundle\Entity\Checklists|null $checklistid
     *
     * @return Servicestoproperties
     */
    public function setChecklistid(\AppBundle\Entity\Checklists $checklistid = null)
    {
        $this->checklistid = $checklistid;

        return $this;
    }

    /**
     * Get checklistid.
     *
     * @return \AppBundle\Entity\Checklists|null
     */
    public function getChecklistid()
    {
        return $this->checklistid;
    }

    /**
     * Set defaultservicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $defaultservicerid
     *
     * @return Servicestoproperties
     */
    public function setDefaultservicerid(\AppBundle\Entity\Servicers $defaultservicerid = null)
    {
        $this->defaultservicerid = $defaultservicerid;

        return $this;
    }

    /**
     * Get defaultservicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getDefaultservicerid()
    {
        return $this->defaultservicerid;
    }
}
