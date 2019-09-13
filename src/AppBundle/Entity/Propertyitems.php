<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Propertyitems
 *
 * @ORM\Table(name="PropertyItems", indexes={@ORM\Index(name="propertyid", columns={"PropertyID"}), @ORM\Index(name="propertyitemid", columns={"PropertyItemTypeID"}), @ORM\Index(name="propertyitemtypeid", columns={"PropertyItemTypeID"})})
 * @ORM\Entity
 */
class Propertyitems
{
    /**
     * @var int
     *
     * @ORM\Column(name="PropertyItemID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $propertyitemid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Store", type="string", length=50, nullable=true)
     */
    private $store;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Brand", type="string", length=250, nullable=true)
     */
    private $brand;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Model", type="string", length=250, nullable=true)
     */
    private $model;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PartNumber", type="string", length=250, nullable=true)
     */
    private $partnumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="SerialNumber", type="string", length=250, nullable=true)
     */
    private $serialnumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Phone", type="string", length=50, nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Warranty", type="string", length=50, nullable=true)
     */
    private $warranty;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=0, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image3", type="string", length=250, nullable=true)
     */
    private $image3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image1", type="string", length=250, nullable=true)
     */
    private $image1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image2", type="string", length=250, nullable=true)
     */
    private $image2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PDF", type="string", length=250, nullable=true)
     */
    private $pdf;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdate = 'CURRENT_TIMESTAMP';

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
     * @var \Propertyitemtypes
     *
     * @ORM\ManyToOne(targetEntity="Propertyitemtypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PropertyItemTypeID", referencedColumnName="PropertyItemTypeID")
     * })
     */
    private $propertyitemtypeid;



    /**
     * Get propertyitemid.
     *
     * @return int
     */
    public function getPropertyitemid()
    {
        return $this->propertyitemid;
    }

    /**
     * Set store.
     *
     * @param string|null $store
     *
     * @return Propertyitems
     */
    public function setStore($store = null)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store.
     *
     * @return string|null
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Set brand.
     *
     * @param string|null $brand
     *
     * @return Propertyitems
     */
    public function setBrand($brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand.
     *
     * @return string|null
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model.
     *
     * @param string|null $model
     *
     * @return Propertyitems
     */
    public function setModel($model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model.
     *
     * @return string|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set partnumber.
     *
     * @param string|null $partnumber
     *
     * @return Propertyitems
     */
    public function setPartnumber($partnumber = null)
    {
        $this->partnumber = $partnumber;

        return $this;
    }

    /**
     * Get partnumber.
     *
     * @return string|null
     */
    public function getPartnumber()
    {
        return $this->partnumber;
    }

    /**
     * Set serialnumber.
     *
     * @param string|null $serialnumber
     *
     * @return Propertyitems
     */
    public function setSerialnumber($serialnumber = null)
    {
        $this->serialnumber = $serialnumber;

        return $this;
    }

    /**
     * Get serialnumber.
     *
     * @return string|null
     */
    public function getSerialnumber()
    {
        return $this->serialnumber;
    }

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return Propertyitems
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set warranty.
     *
     * @param string|null $warranty
     *
     * @return Propertyitems
     */
    public function setWarranty($warranty = null)
    {
        $this->warranty = $warranty;

        return $this;
    }

    /**
     * Get warranty.
     *
     * @return string|null
     */
    public function getWarranty()
    {
        return $this->warranty;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Propertyitems
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
     * Set image3.
     *
     * @param string|null $image3
     *
     * @return Propertyitems
     */
    public function setImage3($image3 = null)
    {
        $this->image3 = $image3;

        return $this;
    }

    /**
     * Get image3.
     *
     * @return string|null
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * Set image1.
     *
     * @param string|null $image1
     *
     * @return Propertyitems
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
     * @return Propertyitems
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
     * Set pdf.
     *
     * @param string|null $pdf
     *
     * @return Propertyitems
     */
    public function setPdf($pdf = null)
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * Get pdf.
     *
     * @return string|null
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Propertyitems
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
     * @return Propertyitems
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
     * Set propertyitemtypeid.
     *
     * @param \AppBundle\Entity\Propertyitemtypes|null $propertyitemtypeid
     *
     * @return Propertyitems
     */
    public function setPropertyitemtypeid(\AppBundle\Entity\Propertyitemtypes $propertyitemtypeid = null)
    {
        $this->propertyitemtypeid = $propertyitemtypeid;

        return $this;
    }

    /**
     * Get propertyitemtypeid.
     *
     * @return \AppBundle\Entity\Propertyitemtypes|null
     */
    public function getPropertyitemtypeid()
    {
        return $this->propertyitemtypeid;
    }
}
