<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plans
 *
 * @ORM\Table(name="Plans", indexes={@ORM\Index(name="IsMonthlyOrYearly", columns={"IsMonthlyOrYearly"})})
 * @ORM\Entity
 */
class Plans
{
    /**
     * @var int
     *
     * @ORM\Column(name="PlanID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $planid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="PlanName", type="string", length=100, nullable=true)
     */
    private $planname;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="IsMonthlyOrYearly", type="boolean", nullable=true)
     */
    private $ismonthlyoryearly;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PlanType", type="integer", nullable=true, options={"comment"="0 = Service COmpany, 1 = Owner, 2 = Property Manager"})
     */
    private $plantype;

    /**
     * @var int|null
     *
     * @ORM\Column(name="BasePrice", type="integer", nullable=true)
     */
    private $baseprice;

    /**
     * @var int|null
     *
     * @ORM\Column(name="BaseQuantity", type="integer", nullable=true)
     */
    private $basequantity;

    /**
     * @var float|null
     *
     * @ORM\Column(name="PricePerAdditional", type="float", precision=53, scale=0, nullable=true)
     */
    private $priceperadditional;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="CreateDate", type="date", nullable=true, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';



    /**
     * Get planid.
     *
     * @return int
     */
    public function getPlanid()
    {
        return $this->planid;
    }

    /**
     * Set planname.
     *
     * @param string|null $planname
     *
     * @return Plans
     */
    public function setPlanname($planname = null)
    {
        $this->planname = $planname;

        return $this;
    }

    /**
     * Get planname.
     *
     * @return string|null
     */
    public function getPlanname()
    {
        return $this->planname;
    }

    /**
     * Set ismonthlyoryearly.
     *
     * @param bool|null $ismonthlyoryearly
     *
     * @return Plans
     */
    public function setIsmonthlyoryearly($ismonthlyoryearly = null)
    {
        $this->ismonthlyoryearly = $ismonthlyoryearly;

        return $this;
    }

    /**
     * Get ismonthlyoryearly.
     *
     * @return bool|null
     */
    public function getIsmonthlyoryearly()
    {
        return $this->ismonthlyoryearly;
    }

    /**
     * Set plantype.
     *
     * @param int|null $plantype
     *
     * @return Plans
     */
    public function setPlantype($plantype = null)
    {
        $this->plantype = $plantype;

        return $this;
    }

    /**
     * Get plantype.
     *
     * @return int|null
     */
    public function getPlantype()
    {
        return $this->plantype;
    }

    /**
     * Set baseprice.
     *
     * @param int|null $baseprice
     *
     * @return Plans
     */
    public function setBaseprice($baseprice = null)
    {
        $this->baseprice = $baseprice;

        return $this;
    }

    /**
     * Get baseprice.
     *
     * @return int|null
     */
    public function getBaseprice()
    {
        return $this->baseprice;
    }

    /**
     * Set basequantity.
     *
     * @param int|null $basequantity
     *
     * @return Plans
     */
    public function setBasequantity($basequantity = null)
    {
        $this->basequantity = $basequantity;

        return $this;
    }

    /**
     * Get basequantity.
     *
     * @return int|null
     */
    public function getBasequantity()
    {
        return $this->basequantity;
    }

    /**
     * Set priceperadditional.
     *
     * @param float|null $priceperadditional
     *
     * @return Plans
     */
    public function setPriceperadditional($priceperadditional = null)
    {
        $this->priceperadditional = $priceperadditional;

        return $this;
    }

    /**
     * Get priceperadditional.
     *
     * @return float|null
     */
    public function getPriceperadditional()
    {
        return $this->priceperadditional;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime|null $createdate
     *
     * @return Plans
     */
    public function setCreatedate($createdate = null)
    {
        $this->createdate = $createdate;

        return $this;
    }

    /**
     * Get createdate.
     *
     * @return \DateTime|null
     */
    public function getCreatedate()
    {
        return $this->createdate;
    }
}
