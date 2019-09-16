<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billingmonthlyitems
 *
 * @ORM\Table(name="BillingMonthlyItems")
 * @ORM\Entity
 */
class Billingmonthlyitems
{
    /**
     * @var int
     *
     * @ORM\Column(name="BillingMonthlyItemID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $billingmonthlyitemid;

    /**
     * @var string
     *
     * @ORM\Column(name="MonthlyItem", type="string", length=100, nullable=false)
     */
    private $monthlyitem;

    /**
     * @var float
     *
     * @ORM\Column(name="Amount", type="float", precision=53, scale=0, nullable=false)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CreateDate", type="datetime", nullable=false, options={"default"="getutcdate()"})
     */
    private $createdate = 'getutcdate()';



    /**
     * Get billingmonthlyitemid.
     *
     * @return int
     */
    public function getBillingmonthlyitemid()
    {
        return $this->billingmonthlyitemid;
    }

    /**
     * Set monthlyitem.
     *
     * @param string $monthlyitem
     *
     * @return Billingmonthlyitems
     */
    public function setMonthlyitem($monthlyitem)
    {
        $this->monthlyitem = $monthlyitem;

        return $this;
    }

    /**
     * Get monthlyitem.
     *
     * @return string
     */
    public function getMonthlyitem()
    {
        return $this->monthlyitem;
    }

    /**
     * Set amount.
     *
     * @param float $amount
     *
     * @return Billingmonthlyitems
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set createdate.
     *
     * @param \DateTime $createdate
     *
     * @return Billingmonthlyitems
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
}
