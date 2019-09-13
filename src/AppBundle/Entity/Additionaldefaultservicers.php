<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Additionaldefaultservicers
 *
 * @ORM\Table(name="AdditionalDefaultServicers", indexes={@ORM\Index(name="IDX_1A0DD39C3C7E7BEF", columns={"ServicerID"}), @ORM\Index(name="IDX_1A0DD39C85991014", columns={"ServiceToPropertyID"})})
 * @ORM\Entity
 */
class Additionaldefaultservicers
{
    /**
     * @var int
     *
     * @ORM\Column(name="AdditionalDefaultServicerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $additionaldefaultservicerid;

    /**
     * @var float|null
     *
     * @ORM\Column(name="PiecePay", type="float", precision=53, scale=0, nullable=true)
     */
    private $piecepay;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';

    /**
     * @var \Servicers
     *
     * @ORM\ManyToOne(targetEntity="Servicers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServicerID", referencedColumnName="ServicerID")
     * })
     */
    private $servicerid;

    /**
     * @var \Servicestoproperties
     *
     * @ORM\ManyToOne(targetEntity="Servicestoproperties")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ServiceToPropertyID", referencedColumnName="ServiceToPropertyID")
     * })
     */
    private $servicetopropertyid;



    /**
     * Get additionaldefaultservicerid.
     *
     * @return int
     */
    public function getAdditionaldefaultservicerid()
    {
        return $this->additionaldefaultservicerid;
    }

    /**
     * Set piecepay.
     *
     * @param float|null $piecepay
     *
     * @return Additionaldefaultservicers
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
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Additionaldefaultservicers
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
     * Set servicerid.
     *
     * @param \AppBundle\Entity\Servicers|null $servicerid
     *
     * @return Additionaldefaultservicers
     */
    public function setServicerid(\AppBundle\Entity\Servicers $servicerid = null)
    {
        $this->servicerid = $servicerid;

        return $this;
    }

    /**
     * Get servicerid.
     *
     * @return \AppBundle\Entity\Servicers|null
     */
    public function getServicerid()
    {
        return $this->servicerid;
    }

    /**
     * Set servicetopropertyid.
     *
     * @param \AppBundle\Entity\Servicestoproperties|null $servicetopropertyid
     *
     * @return Additionaldefaultservicers
     */
    public function setServicetopropertyid(\AppBundle\Entity\Servicestoproperties $servicetopropertyid = null)
    {
        $this->servicetopropertyid = $servicetopropertyid;

        return $this;
    }

    /**
     * Get servicetopropertyid.
     *
     * @return \AppBundle\Entity\Servicestoproperties|null
     */
    public function getServicetopropertyid()
    {
        return $this->servicetopropertyid;
    }
}
