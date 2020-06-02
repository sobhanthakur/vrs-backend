<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 *
 * @ORM\Table(name="Images", indexes={@ORM\Index(name="PropertyID", columns={"PropertyID"}), @ORM\Index(name="SortOrder", columns={"SortOrder"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImagesRepository")
 */
class Images
{
    /**
     * @var int
     *
     * @ORM\Column(name="ImageID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $imageid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PDF", type="string", length=100, nullable=true)
     */
    private $pdf;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Image", type="string", length=250, nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="EmbedTag", type="text", length=-1, nullable=true)
     */
    private $embedtag;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ImageTitle", type="string", length=250, nullable=true)
     */
    private $imagetitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ImageDescription", type="string", length=5000, nullable=true)
     */
    private $imagedescription;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="Internal", type="boolean", nullable=true, options={"default"="1"})
     */
    private $internal = '1';

    /**
     * @var string|null
     *
     * @ORM\Column(name="ServiceIDs", type="text", length=-1, nullable=true)
     */
    private $serviceids;

    /**
     * @var int
     *
     * @ORM\Column(name="SortOrder", type="integer", nullable=false)
     */
    private $sortorder;

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
     * Get imageid.
     *
     * @return int
     */
    public function getImageid()
    {
        return $this->imageid;
    }

    /**
     * Set pdf.
     *
     * @param string|null $pdf
     *
     * @return Images
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
     * Set image.
     *
     * @param string|null $image
     *
     * @return Images
     */
    public function setImage($image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set embedtag.
     *
     * @param string|null $embedtag
     *
     * @return Images
     */
    public function setEmbedtag($embedtag = null)
    {
        $this->embedtag = $embedtag;

        return $this;
    }

    /**
     * Get embedtag.
     *
     * @return string|null
     */
    public function getEmbedtag()
    {
        return $this->embedtag;
    }

    /**
     * Set imagetitle.
     *
     * @param string|null $imagetitle
     *
     * @return Images
     */
    public function setImagetitle($imagetitle = null)
    {
        $this->imagetitle = $imagetitle;

        return $this;
    }

    /**
     * Get imagetitle.
     *
     * @return string|null
     */
    public function getImagetitle()
    {
        return $this->imagetitle;
    }

    /**
     * Set imagedescription.
     *
     * @param string|null $imagedescription
     *
     * @return Images
     */
    public function setImagedescription($imagedescription = null)
    {
        $this->imagedescription = $imagedescription;

        return $this;
    }

    /**
     * Get imagedescription.
     *
     * @return string|null
     */
    public function getImagedescription()
    {
        return $this->imagedescription;
    }

    /**
     * Set internal.
     *
     * @param bool|null $internal
     *
     * @return Images
     */
    public function setInternal($internal = null)
    {
        $this->internal = $internal;

        return $this;
    }

    /**
     * Get internal.
     *
     * @return bool|null
     */
    public function getInternal()
    {
        return $this->internal;
    }

    /**
     * Set serviceids.
     *
     * @param string|null $serviceids
     *
     * @return Images
     */
    public function setServiceids($serviceids = null)
    {
        $this->serviceids = $serviceids;

        return $this;
    }

    /**
     * Get serviceids.
     *
     * @return string|null
     */
    public function getServiceids()
    {
        return $this->serviceids;
    }

    /**
     * Set sortorder.
     *
     * @param int $sortorder
     *
     * @return Images
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
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Images
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
     * @return Images
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
}
